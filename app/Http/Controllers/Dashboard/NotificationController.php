<?php

namespace App\Http\Controllers\Dashboard;


use App\Models\Notification;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use App\Models\AppData;
use App\Models\User;
use App\Helpers\OneSignalHelper;
use Illuminate\Support\Facades\Log;
use App\Events\NotificationCreated;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::latest()->paginate(25);
        return view('dashboard.notifications.index', compact('notifications'));
    }

    public function getFirebaseClientUrl()
    {
        //new code to fix noticiation issues
        $serviceAccountFile = public_path('service-account.json');
        if (!file_exists($serviceAccountFile)) {
            return NULL;
        }
        $scopes = ['https://www.googleapis.com/auth/cloud-platform'];
        $jsonKey = json_decode(file_get_contents($serviceAccountFile), true);
        $credentials = new ServiceAccountCredentials(
            $scopes,
            $jsonKey
        );
        $httpHandler = HttpHandlerFactory::build();
        try {
            $credentials->fetchAuthToken($httpHandler);
            $accessToken = $credentials->getLastReceivedToken();

            if (isset($accessToken['access_token'])) {
                $accessTokenUsable = $accessToken['access_token'];
            } else {
                return NULL;
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return NULL;
        }

        $client = new Client([
            'headers' => [
                'Authorization' => 'Bearer ' . $accessTokenUsable,
                'Content-Type' => 'application/json',
            ],
        ]);
        return $client;
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        $message = $request->title . ' :- ' .$request->description;
        if($message){
            try {
                OneSignalHelper::allUsersNotification($message, $request->url);
            } catch (\Throwable $th) {
                Log::error('Notification Error: ' . $th->getMessage());
            }
        }

        Notification::create($request->all());

        // Call the Event and Send notification real time
        $notification = [
            'title' => $request->title,
            'description' => $request->description,
        ];
        event(new NotificationCreated($notification));        
        
        $client = $this->getFirebaseClientUrl();
        if ($client == NULL) {
            return back()->with('success', 'Firebase Client Error, Could not send notification');
        }

        try {
            $response = $client->post(env('FIREBASE_URL'), [
                'json' => [
                    'message' => [
                        // 'token' => '$userToken',
                        'topic' => 'daily_messaging_all_users',
                        'notification' => [
                            'body' => $request->description,
                            'title' => $request->title,
                        ],
                    ],
                ],
            ]);
            $body = $response->getBody();
        } catch (\Throwable $th) {
        }
        return back()->with('success', 'Notification is stored');
    }
}
