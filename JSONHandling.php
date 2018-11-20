<?php
    $json = '{"header": {
        "user_id": "23556",
        "account_type": "1",
        "short_limit": "12",
        "long_limit": "300",
        "long_remaining": 293,
        "short_remaining": 11,
        "status": 0,
        "results_requested": 16,
        "index": {
            "5": {
                "status": 0,
                "parent_id": 5,
                "id": 5,
                "results": 1
            },
            "51": {
                "status": 0,
                "parent_id": 5,
                "id": 51,
                "results": 1
            },
            "52": {
                "status": 0,
                "parent_id": 5,
                "id": 52,
                "results": 1
            },
            "6": {
                "status": 0,
                "parent_id": 6,
                "id": 6,
                "results": 1
            }
        },
        "search_depth": "128",
        "minimum_similarity": 55,
        "query_image_display": "userdata/2hQOvFB71.gif.png",
        "query_image": "2hQOvFB71.gif",
        "results_returned": 3
        },
        "results": [
            {
                "header": {
                    "similarity": "23.50",
                    "thumbnail": "https://img1.saucenao.com/res/pixiv/493/4933944_s.jpg?auth=GOzVtrYqKhBVprMkTmuKSg&exp=1541711039",
                    "index_id": 5,
                    "index_name": "Index #5: Pixiv Images - 4933944_s.jpg"
                },
                "data": {
                    "ext_urls": [
                        "http://www.pixiv.net/member_illust.php?mode=medium&illust_id=4933944"
                    ],
                    "title": "妖キャラをカリスマ化してみた。",
                    "pixiv_id": 4933944,
                    "member_name": "佳虫",
                    "member_id": 724886
                }
            },
            {
                "header": {
                    "similarity": "21.48",
                    "thumbnail": "https://img1.saucenao.com/res/pixiv_historical/383/3836606_s.jpg?auth=_gdkaLDeuvrIPog4WHozzQ&exp=1541711039",
                    "index_id": 6,
                    "index_name": "Index #6: Pixiv Historical - 3836606_s.jpg"
                },
                "data": {
                    "ext_urls": [
                        "http://www.pixiv.net/member_illust.php?mode=medium&illust_id=3836606"
                    ],
                    "title": "【pixiv袁紹軍】",
                    "pixiv_id": 3836606,
                    "member_name": "白菜",
                    "member_id": 58228
                }
            },
            {
                "header": {
                    "similarity": "21.15",
                    "thumbnail": "https://img1.saucenao.com/res/pixiv/6070/manga/60706368_p2.jpg?auth=lU6uvBSzyHfG9fBGcjRybQ&exp=1541711039",
                    "index_id": 5,
                    "index_name": "Index #5: Pixiv Images - 60706368_p2.jpg"
                },
                "data": {
                    "ext_urls": [
                        "http://www.pixiv.net/member_illust.php?mode=medium&illust_id=60706368"
                    ],
                    "title": "MHAまとめ9",
                    "pixiv_id": 60706368,
                    "member_name": "まゆ",
                    "member_id": 361946
                }
            }
        ]}';
    $decodedJson = json_decode($json);

    // Decode JSON data to PHP object
    $obj = json_decode($json);
    // Access values from the returned object
    if(property_exists($obj->results[0], 'data')) {
        print_r($obj->results[0]->data);
    }
    