/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

function getBidsDetail() {
    let game_id = $("select[name='game_type_id']").val();
    let market_time = $("select[name='market_time']").val();
    if (game_id == "") {
        alert("Please select game type");
        return;
    }
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        method: "POST",
        url: "/getBidsDetail",
        data: {
            game_id,
            market_time,
        },

        success: function (response) {
            console.log(response);
            if (response.status == "success") {
                // if(response.bids)
                if (response.bids[0]) {
                    $("#ank_0_bids").html(response.bids[0].ank_bids);
                    $("#ank_0_amount").html(response.bids[0].ank_amount);
                }
                if (response.bids[1]) {
                    $("#ank_1_bids").html(response.bids[1].ank_bids);
                    $("#ank_1_amount").html(response.bids[1].ank_amount);
                }
                if (response.bids[2]) {
                    $("#ank_2_bids").html(response.bids[2].ank_bids);
                    $("#ank_2_amount").html(response.bids[2].ank_amount);
                }
                if (response.bids[3]) {
                    $("#ank_3_bids").html(response.bids[3].ank_bids);
                    $("#ank_3_amount").html(response.bids[3].ank_amount);
                }
                if (response.bids[4]) {
                    $("#ank_4_bids").html(response.bids[4].ank_bids);
                    $("#ank_4_amount").html(response.bids[4].ank_amount);
                }
                if (response.bids[5]) {
                    $("#ank_5_bids").html(response.bids[5].ank_bids);
                    $("#ank_5_amount").html(response.bids[5].ank_amount);
                }
                if (response.bids[6]) {
                    $("#ank_6_bids").html(response.bids[6].ank_bids);
                    $("#ank_6_amount").html(response.bids[6].ank_amount);
                }
                if (response.bids[7]) {
                    $("#ank_7_bids").html(response.bids[7].ank_bids);
                    $("#ank_7_amount").html(response.bids[7].ank_amount);
                }
                if (response.bids[8]) {
                    $("#ank_8_bids").html(response.bids[8].ank_bids);
                    $("#ank_8_amount").html(response.bids[8].ank_amount);
                }
                if (response.bids[9]) {
                    $("#ank_9_bids").html(response.bids[9].ank_bids);
                    $("#ank_9_amount").html(response.bids[9].ank_amount);
                }
            }
            return;
        },
    });
}
