<?php
require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php"); // 설정 파일 호출
require_once(FILE_LIB_DB); // DB관련 라이브러리
$list_cnt = 4; // 한 페이지 최대 표시 수
$page_num = 1; // 페이지 번호 초기화
try {
  $conn = my_db_conn(); // connection 함수 호출
  $page_num = isset($_GET["page"]) ? $_GET["page"] : $page_num; // 파라미터에서 page 획득
  $no = isset($_GET["no"]) ? $_GET["no"] : $no; // 파라미터에서 no 획득
  $result_board_cnt = db_select_boards_cnt($conn); // 게시글수조회
  $max_page_num = ceil($result_board_cnt / $list_cnt); // 최대 페이지 수
  $offset = $list_cnt * ($page_num-1);
  $prev_page_num = ($no - 1) < 1 ? 1 : ($no - 1); // 이전 게시글 페이지 번호
  $next_page_num = ($no + 1) > $max_page_num ? $max_page_num : ($no + 1); // 다음 게시글 페이지 번호
  // 게시글 리스트
  $arr_param = [
    "list_cnt" => $list_cnt
    ,"offset" => $offset
  ];
  $result = db_select_boards_paging($conn, $arr_param);
  $tmp_now_item; // 현재 페이지 게시글 정보 임시 보관용
  // 현재 페이지 게시글 획득
  foreach($result as $key => $item) {
    if($no == $item["no"]) {
      $tmp_now_item = $item;
      unset($result[$key]);
      break;
    }
  }
  $result[] = $tmp_now_item; // 현재 게시글 result에 추가
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
    <title>상세 페이지</title>
    <link rel="stylesheet" href="./css/board.css">
</head>
<body>
    <header>
      <h1><a href="./list.php">Pink Board</a></h1>
        <hr>
    </header>
    <main>
        <div class="header">
        <a href="./update.php?no=<?php echo $no?>&page=<?php echo $page_num?>"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEYAAABGCAYAAABxLuKEAAAACXBIWXMAAAsTAAALEwEAmpwYAAACsklEQVR4nO2bzWpTURDHj1C/llJQ924VBDfGZxBB38CNCloE38CloO59ga4U8QM/EAyCCLF3TmssLkoz00hAF5bYnZo5HTltrr1NJiYtLuSc+cHZ5OYu7o+Z/zmLOc4ZhmEYu0ZE9vQKOh88PWJPbQbsBKBXDHRR6vUplyPy7vPB4PFB8CTaYsC30uhMu1yQheXDAlSLlTFKyp8F+DpWlUsdAaoxYHeskMrqAV1wyUvxtLYTKf2qmXWpIruVspE19N5lIQXwKUPrGgOuTyTG0xuXgZRnsrS0Pz5jwMsTyrnl0m4ffC51OlD9zzg57OmXFO1jLt1KoZeDUiaRwx6vu1SlsKcP8TD3t3dUOUA3Xcq7DwOGeMQf924M5Op7UrROuNS3ZB4jJwZy3K0qVfZdmq0jLodzCgOuM9CVoXcXF/cFT48rUtakoNMup8MbD8jZqBRPT7KWEgbkKO2Tr5RQzRxPzW1SgGouZylh+BBnlRJMClmlBGsfskypYkGrYFIUTIqCSVEwKQomRcGkKJgUBZOiYFIUTIqCSVEwKQomRcGkKJgUBZOiYFIUTIqCSVEwKQomRcGkKJgUkzIZ0mwfYo/f/tnUQQqjGNpAX/ajGCXsaW5r/BNns6+UiCwsH9+aUKKP8TcuVq4GT/fiYo+fsmqfkgB4uyLmxtDzTUHpTzJVkXp9ij1+2fxA7EmDju5ETJJSIr2idbaSLQ+dQr+t8mifkuDxfvmhPcBzI/83EMhJS5FGZ5qBfvSz5asUxd6hsw3gJQacz6J9ShhwpjIv2xVon4qZEysntlW846PM1a4mWykl7BEGPrpbBrGSJ3Mxa2IVuZSpnl3C6Knr1bgjyTyddLkQPN7RZdDPGMhxt8ry6n/w9GJ7qyDEzMnqqr+G+JUz8dJl8Hg3mRtghmEY7v/iN52BDrLaQ7XcAAAAAElFTkSuQmCC"></a>
                <div></div>
                <a href="./delete.php?no=<?php echo $no?>&page=<?php echo $page_num?>"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEYAAABGCAYAAABxLuKEAAAACXBIWXMAAAsTAAALEwEAmpwYAAACQElEQVR4nO2YP49OURCHj0Qhev8aWjZRiQg+Ah+BchXCNtvzDdCIEt8AjajeoJLcmVeWir0zV2S3pbDVe46RW0luFHs3zsyZZJ7kV79nnty3eVIKgiAIgiAIgiAIgkAZ6frzeUnrGeiWiy1pfXxzZSl0KSOtCrJ42vhmwf5iNTEZedP6yIPL4c16YoBuuhUD/Y1qYmQ5XLU+8KATHK7UE/OBT7oVs9WfqCdG5FAG+mV95NxlpL3x7akmGfiTPzG8lWpTkF9aHzp7wC8UxNBD80Nnjx5UF5ORNuwPnbcMdLe6mFXXX7c+dO5Wy+FadTGCw5r1oXMnHZ+tL2bBRzLQb+tj97vxreObkwYZacePGP6uImUkI79zIwb5bdKiAD+zPnjfA3qqJiYj3ffzxdA9PTHgJz9UzQ2e80PV3DBFuuGUGzE1c8M/8wPSXvN/I43cMCUDfW5fjEJumFKQX1kf3kRu8JkfFHKDx/ygkhs85geV3OAxP6jkBm/5QTU3eMoPqrlhSkZ+36wYzdwwpQA/txbQRG7wlB9Uc4On/KCaGzzlBwG+nKyQhvODam7wkh9McoOH/GCSG1zkB4vcMKUAPzIX0UJu8JAfTHKDh/xgkhs85AeT3NB6fjDNDS3nB9PcMKUAv7EW8nf0OrVChv5OM18M8u3UCtLtHM1I2w1I+Tq+JbWE4LCWkXbtpNCuAJ9LLSIft48XpCcZ+aeaEKAfBeix4JdjqXVksTgsQGcEvl2ouq4/Pf6W9b1BEARB+j/8AV7O+L6pmWhLAAAAAElFTkSuQmCC"></a>
        </div>
        <div class="section">
            <?php
            for($i = 1; $i <= $list_cnt; $i++)
            {
            ?>
                <input type = "radio" name="slide" id="slide0<?php echo $i ?>" checked>
            <?php
            }
            ?>
            <div class="slidewrap">
                <ul class="slidelist">
                    <?php
                    $cnt = 1;
                    foreach($result as $item) {
                        $cnt++;
                    ?>
                        <li>
                            <a href="./detail.php?no=<?php echo $prev_page_num ?>"><label for = "slide0<?php echo $cnt <= $list_cnt ? $cnt : "1"; ?>" class="left"></label></a>
                            <div class="item-no"><?php echo $item["no"]?></div>
                            <div class="item-title"><?php echo $item["title"]?></div>
                            <div class="item-createdat"><?php echo $item["created_at"]?></div>
                            <div class="line-title"><?php echo $item["content"]?></div>
                            <a href="./detail.php?no=<?php echo $next_page_num ?>"><label for = "slide0<?php echo $cnt <= $list_cnt ? $cnt : "1"; ?>" class="right"></label></a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </main>
</body>
</html>














