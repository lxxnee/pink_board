<?php
require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php"); // 설정 파일 호출
require_once(FILE_LIB_DB); // DB관련 라이브러리
$list_cnt = 4; // 한 페이지 최대 표시 수
$page_num = 1; // 페이지 번호 초기화

try {
  $conn = my_db_conn(); // connection 함수 호출
  $page_num = isset($_GET["page"]) ? $_GET["page"] : $page_num; // 파라미터에서 page 획득
  $result_board_cnt = db_select_boards_cnt($conn); // 게시글수조회
  $max_page_num = ceil($result_board_cnt / $list_cnt); // 최대 페이지 수
  $offset = $list_cnt * ($page_num-1);
  $prev_page_num = ($page_num - 1) < 1 ? 1 : ($page_num - 1); // 이전 버튼 페이지 번호
  $next_page_num = ($page_num + 1) > $max_page_num ? 1 : ($page_num + 1); // 다음 버튼 페이지 번호
  // 게시글 리스트
  $arr_param = [
    "list_cnt" => $list_cnt
    ,"offset" => $offset
  ];
  $result = db_select_boards_paging($conn, $arr_param);
} catch (\Throwable $e) {
  echo $e->getMessage();
  exit;
} finally {
  if(!empty($conn)){
    $conn = null;
  }
}

?>


<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>메인 페이지</title>
    <link rel="stylesheet" href="./css/board.css ">
</head>
<body>
  <header>
      <h1><a href="./list.php">Pink Board</a></h1>
      <hr>
  </header>
  <main>
      <section> 
          <a href="./list.php?page=<?php echo $prev_page_num ?>" class = "a"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAYAAAA6/NlyAAAACXBIWXMAAAsTAAALEwEAmpwYAAADIklEQVR4nO2aTU8UQRCGOxHxrolI0JuKv8ZATDRw8mCEqODBaDz6A0TwN+iB4IEbhtMeEZiqYckeCMtWbYg3PxJAEnVraNO7BNfsarI93fMh8yadTDJf+/Rb2z1d1UoVKlSoUKFTKr3KlwR5PAJ+HQEvC3JVgL4K8s9max1XzTlzjSCPmXtUnqRXP14QpGlBXo+QtU0T4DUBmjLPUlmVLm9fjoDnBOnQFrQTnL5FSLM63B1SWZEOgrOC9FiAD1yBdoCbTgR+obe3z6ULW6ZhAd7wBdrF8VBv1K6nAtuA+i2frv7j/73fCHg0UVgJ+a4gNZKGbQtxEeDJZGCBJtIC7RLiU/7DGEnSBm13uhHyiBdYDdWrgryXNmSny3ygA77hFrZS6fc2GgMtmjm8NY/TkmVoo5kenQEL8nNPDs3rUqnvpGMNtK3TIT11AqvD3aHWF49f2Oa71qpX4oV2fTA2cAQ8lwhsqdRnwjvec2nGxULgMBFY5Pm4zzaRGGvBIUjTTmGBFzpgF/SZCOitq3dIUH8YA5jX8+Dsny7zqhWsDuqDAnSUF2fbwvpIb9YGbNwdzxtsG/SdnoEjk5bJSRh3Npq1AV7Om7O/301LPQMLUi1/zh6HNHK1d2CgL5a9u5gmbAuYPlsA8w+bl5nv4TRhm8DA3wvg/zuk+VPvwEg7p2rQiuJPS++6TkvIbzI5LUWn7cNDkMfc9HbyTktAt3sG1pu1AYeLh8SgrRcPRqaKl7vlIdKKspUATTl1IAGnBflBvBQPOE/gZTfFY2RGPMfA/pJ4QC9VrtK05Rh5aeB9Z9slBOiZB+AO6Fh5aeQnTmBPqvxA6LXUYmBtSy1I4LTUkvliWpmGlQ81Ah7NXLkU6KbyKQG6nw1n6Uiwds8rbLa2PNCESlKNkEfMVJA8LO95D+O/SePONW+jd3dnwQyeKk3pUqnveGPavveNaZVKv8qKdFAfjJBeufwqO956OJPpDae6ueCoPRLkDzbr6dboSyum7Kkru+dVnqQ3di6awlZrAULvBXjLZENNzrvVmsdb5py5xlxr7kn7dxcqVKhQIZWSfgF2vT+2j9UW3AAAAABJRU5ErkJggg=="></a> 
          <div class="date b"><?php echo date("Y-m-d")?></div> 
          <a href="./list.php?page=<?php echo $next_page_num ?>" class= "c"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAYAAAA6/NlyAAAACXBIWXMAAAsTAAALEwEAmpwYAAADHUlEQVR4nO2azU4UQRDHJxHxrolI0JuKT2MgJho4eTBiVPBgNB59ABF8Bj0QPHDDcNojglM1C9kDYdmqDfHmRwJIom4NbYpFJNnFZHu75yM7/2SSSaZn07+p2q6uqg6CQoUKFSrUozIrfEmQx2PgNzHwkiBXBei7IP8+vJr3VX2mYwR5TN8J8iSz8vmCIE0J8qcY2dhcArwqQJP6W0FWZdY2L8fAs4K0bwvaCk4/YqQZE20PBVmRCcOzgvREgPdcgbaA60cEfmk2N8+lC7tGwwJc9gXaxuKRKdeupwLbgPotn1b9z/97txHyaKKwEvFdQWokDXvCxUWAHyQDCzSRFmgbF5/078ZIkjboSUs3Ih7xAmugelWQd9KGbLUy75mQb7iFrVT6rVdjoEWN0c04TQueXBs1PDoDFuQXtpNR0OMPVyr1xchzXqAjeuYE1kTbQ80dT/fAPqGbrl0f7Bo4Bp7tajJACwqZBHSMNO0iEdh3MJm5JKDVE7tKOARpytmEgN+3QM+bMzHyW6fQYf1RF8BsneKlZWkBXrGCNWF9UIAOHAN7t7TO2azXBmysO+4cNjnoOx0Dx1qW8QXs3b1pxgZ4yTOwP0sDLXYMLEg178CeLK2Fwc6Bgb4lBHw6tOXeW5C+WgDzrwSB20NrwmEDDPwzn8Cr1SsJAlOOXZq/dA6MtNVTi1acTFiabxuWgN4lHpbiXtt4CPJY7iz716VDut0xsFmvDXhKHvzC2iYPKu3i5ceNj4CRlgNbCdBkXiz7D5gfdlfiAfsCXpKWdVLiUemKl5siHtCroIfKtLvOjksI0PPMF+KRnzqBPe7yA2FXrRZNBHy1WpDAaasl8820NRoOfKgR8mjm2qVANwOfEqD72bAsHQjW7nmFzdaRB5oIklQj4hENBcnD8o53Nz5NBreuWa/edpYFXTyDNGVKpb6jg2m73g+mVSr9QVZkwvpgjPTa0d775NHD6UwfODWHCUftsSB/tMmnm6svLWvb01S2zwd5kilvXdTGVjMBoQ8CvKHVUC0BN6/D+w19pmN0rL6T9rwLFSpUqFCQkv4AJZU/uxDrk90AAAAASUVORK5CYII="></a>
          <a href="./insert.php" class="d"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEYAAABGCAYAAABxLuKEAAAACXBIWXMAAAsTAAALEwEAmpwYAAACsklEQVR4nO2bzWpTURDHj1C/llJQ924VBDfGZxBB38CNCloE38CloO59ga4U8QM/EAyCCLF3TmssLkoz00hAF5bYnZo5HTltrr1NJiYtLuSc+cHZ5OYu7o+Z/zmLOc4ZhmEYu0ZE9vQKOh88PWJPbQbsBKBXDHRR6vUplyPy7vPB4PFB8CTaYsC30uhMu1yQheXDAlSLlTFKyp8F+DpWlUsdAaoxYHeskMrqAV1wyUvxtLYTKf2qmXWpIruVspE19N5lIQXwKUPrGgOuTyTG0xuXgZRnsrS0Pz5jwMsTyrnl0m4ffC51OlD9zzg57OmXFO1jLt1KoZeDUiaRwx6vu1SlsKcP8TD3t3dUOUA3Xcq7DwOGeMQf924M5Op7UrROuNS3ZB4jJwZy3K0qVfZdmq0jLodzCgOuM9CVoXcXF/cFT48rUtakoNMup8MbD8jZqBRPT7KWEgbkKO2Tr5RQzRxPzW1SgGouZylh+BBnlRJMClmlBGsfskypYkGrYFIUTIqCSVEwKQomRcGkKJgUBZOiYFIUTIqCSVEwKQomRcGkKJgUBZOiYFIUTIqCSVEwKQomRcGkKJgUkzIZ0mwfYo/f/tnUQQqjGNpAX/ajGCXsaW5r/BNns6+UiCwsH9+aUKKP8TcuVq4GT/fiYo+fsmqfkgB4uyLmxtDzTUHpTzJVkXp9ij1+2fxA7EmDju5ETJJSIr2idbaSLQ+dQr+t8mifkuDxfvmhPcBzI/83EMhJS5FGZ5qBfvSz5asUxd6hsw3gJQacz6J9ShhwpjIv2xVon4qZEysntlW846PM1a4mWykl7BEGPrpbBrGSJ3Mxa2IVuZSpnl3C6Knr1bgjyTyddLkQPN7RZdDPGMhxt8ry6n/w9GJ7qyDEzMnqqr+G+JUz8dJl8Hg3mRtghmEY7v/iN52BDrLaQ7XcAAAAAElFTkSuQmCC"></a>
      </section>
      <div class="center">
        <div class="list-head">
          <div>게시글 번호</div>
          <div>게시글 제목</div>
          <div>작성일자</div>
        </div>
        <?php foreach($result as $item){
        ?>
        <div class="list-item">
              <div><?php echo $item["no"]?></div>
              <div><a href="./detail.php?no=<?php echo $item["no"]?>&page=<?php echo $page_num?>"><?php echo $item["title"] ?></a></div>
              <div><?php echo $item["created_at"] ?></div>
        </div>
        <?php }?>
      </div>
    </main>
  </body>
</html>
          

        


  