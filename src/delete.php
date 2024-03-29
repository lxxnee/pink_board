<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/config.php"); // 설정파일 호출
require_once(FILE_LIB_DB); // DB관련 라이브러리


try {
    $conn = my_db_conn();
    
    if(REQUEST_METHOD === "GET"){
        $no = isset($_GET["no"]) ? $_GET["no"]  : "";  // no 파라미터 획득
        $page = isset($_GET["page"]) ? $_GET["page"] : ""; // page 파라미터 획득

        $arr_err_param = [];
        if($no === ""){
            $arr_param = "no";
        }
        if($page === ""){
            $arr_param = "page";
        }
        if(count($arr_err_param) > 0){
            throw new Exception("Parameter Error: ".implode(",", $arr_err_param));
        }

        
        $arr_param = [
            "no" => $no
        ];
        
       
        $result = db_select_boards_no($conn, $arr_param);
        if(count($result)!== 1 ) {
           
            throw new Exception("Select Boards no count");
        }
        $item = $result[0];
    }
    else if(REQUEST_METHOD === "POST"){
        $no = isset($_POST["no"]) ? $_GET["no"]  : "";
        $page = isset($_POST["page"]) ? $_GET["page"]  : "";
        
        $arr_err_param = [];
        if($no === ""){
            throw new Exception("Parameter Error: no");
        }

        $conn->beginTransaction();
        $arr_param = [
            "no" => $no
        ];
        $result = db_delete_boards_no($conn, $arr_param);
        
        if($result !== 1){
            throw new Exception("Delete Boards no count");
        }

        $conn->commit();
        header("location:./list.php"); // 수정하기!!!!!TODO
        exit;

    }

} catch (\Throwable $e) {
    if(!empty($conn)) {
        $conn->rollBack();
    }echo $e->getMessage();
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>삭제 페이지</title>
    <link rel="stylesheet" href="./css/board.css " />
  </head>
  <body>
    <header>
      <h1><a href="./list.php">Pink Board</a></h1>
      <hr />
    </header>
    <form action="./delete.php?no=<?php echo $no?>&page=<?php echo $page ?>" method="post">
    <div class="delete">
      <p>삭제하시겠습니까?</p>
      <p>되돌릴 수 없어요 o(T ヘ To)!!</p>
    </div>
    <div class="header">
        <input type="hidden" name="no" value="<?php echo $no;?>">
        <input type="hidden" name="page" value="<?php echo $page;?>">
      <button type="submit" class="a-button small-button">
        <img
          src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEYAAABGCAYAAABxLuKEAAAACXBIWXMAAAsTAAALEwEAmpwYAAADYklEQVR4nO2bTahNURSAl59CkZ+EJKSIlAwMlAHFQClFYqAIUyaKgZIRZpJCMZCiUErKgNItPa+e7trn0fPT65217vWfn5CQzjqW9nv+el3eOfue59637/7qzO497fudtfZZe+19AQKBQCAQCAQCgWZGkLcJ0lNB+pYiX1eMF0Grk5R5Xa8Qw/rzEuSvYniflkojoRXR8qPJgvzyTyn9BHVomedDq5Eauvw3Kb/kGPosSLugVZCosnkgKf2iZzf4jmJ1uiC9zSnmJfhOinQtj5QfKfUJfEZMZUdeKb0X0nnwFUWaJYY/5I8Wfq/leCb4iKoOs8WbS7SIoS3gK4K0yymFDF8FX9E71TmC/NEhUt5oB08DH1HV4WL4llMKlWkj+Iog7XVMoQvgK4q8QAx9yR0pyK+0s2cK+IiWSiMF+Y5LtCTI68FXxNABx0LuDPiKRrzY9lTypxA91XvVieAj2t09SgzfdZDyLUFeDb6SIh12TKET4Cta5qViSByihbTt4TjwES3xaEG67yAlVUPLwVdS5KNuhRwdAV/RqLLMPnmHQu6Rtj8e0+DB8wSJaI99QkXO/trVNVYMxbmlGBI7JxU1DrfBd8bzxHC13+CO2R5JvfdODZ10fAsdgkaipmeuLZxqD45P2dWv670TE6/qv1mWccK9bydraGik4F+k1ClHy/H4GlGYJYUSxZ4lg/OLM6cPPcsW1vnlpMhnXVJIDO2HRqH34qmZpfzO+eNZ55wEaa2jlHJD96NTw8ccJ8QB5fTuNxt64SDli0bVhdBIUuQbTmIyyEkNX3SKloj2/F8LtQaPdNBZzD/kCNImJylIt/WSjoBGo20Px4mhB0XKsd1627V3SKFPtmSAZkE7e6YIUld9cn6/rbIc2agdLfFOaDa0IDmCvN3t+3SziOp6UNC+FKgrrZx6LIY/aMSzoZnRIiInt5jKDhgK6P+Ug3yjaVOoUXIE6Z3e7Z4BQw0dZDn2bB0MVXSw5CBfgaGOFixHDL+2i1fwAS1QTmLiDeATWkgRSOfAR7QOOWLouXY9ngS+oo5ykqiyBnxH88pBPg2tgmaUI8hP7F4VtBI6wMLT7jomWFkJrYj2NdTba0lpqb/J1MK2I8XQVnui2+4z28PIScQran44EAgEAoFAIBCAgvkOLcroWstO3EQAAAAASUVORK5CYII="
        />
      </button>
      <div></div>
      <a href="./detail.php?no=<?php echo $no?>&page=<?php echo $page?>"
        ><img
          src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAYAAACOEfKtAAAACXBIWXMAAAsTAAALEwEAmpwYAAAEiElEQVR4nO2bTYgdRRDHK4lGRUEXv9CoqKgHP4hBEL8wInhQET2IEiIG1HjwA1TwIHjyAxJQ8CB6EvUgYhSJEQQxYfVgwGW65mV5ibLZqZq3qxjUxO/VdapT0hs3rC9h93XP7DLzXv9gjm/qzb+rqru6qwEikUgkEolEIpFIJBKJRCKRiEOT7OTCZHdIkj8qSBs0za6GRUZb42dIQveIyR4TpPvV7L0ImoaOjR1nkTcL0p8WWec+grxLTeeqym2qLrfIz4vh6W6b1vBn2sougSag7fZK94eP+Ij/iUhTRZLfXKVdi/Ty/Db5F23xGqg7ktLT833I4Q8y/IPuGjunCpsF0p1i6OCCNpH2OE+FOiNIWS8C/hdan6rqsjL2NPnmNDG8r1ebirQW6oqOdoZ6Fm/WK1LaWMamRXrXy56hh6GuaDqxyltA5F81yc4LsVdgdrf/gOXroc4TiCD95ftRIaGsOHa6y6O+tmo/G1tDn3gLGBDK1vD73jYMfw51p0j5piABPUJZDN0bIN60Iq2GJmCR3ggRsZdQ1tHsTEH+MUDAJ6EpaLt9kltzLUYoW6QPAgZma9nl0pKjrfHLj1bKlQllMdl9AZ435mpyaCKS0sagUEba0e0xmuRniaH9foNBU5ryldBkrOG3qwhli/ShvzfTBmg62vr+xJB8ODeUnRD+eY9eg35BA/PhTCinE6vE0AG/vEep7pw4AfoJMfxAUCgjfecp3n5N+XzoR6zht8ImlZ7FswXyrdCvqMuHhnYvmoiGn4N+R9POZWLoj+oFpB26RVfAICDI66oNXZ50uzMwSFhDb1YiHvI/mubXw6Chw3y8GG5VMHE8DoOKtnhNSQE/gkHGIm8qF7403tiNgrIo0lpBkgry4LbGbVWVRd15hmeF0TebpWVR1WUud1W6jEEqNMlugEFAEn6iSvHmeOGkO2SHfkaT7IqgY8+eH9ret9WIHtoX/HrxxJv1RHoW+hEbelLnL6AtMLsF+gkJOscl6/pYBOmngHy4T03nbOgHdGTvub6HQTOPoZfc74s0v72X1rUjRETaqUlyLDQZHR4+Rgx9GeB9u12tPPsei/RqYEhvgiZjkV4ICL+/u1swXMuwO+cIGIiDRcp3QRNRQzeGlGpi+Kmjvg/HLxbDvweIeEBb+QXQJHS0MyTIHf+8xV/M14IrmD0UEspieMR5MTQFa3hrgKf87CacBd+N/F5QPjT8CjQBSfP1QV6CvK6X92vKp4ghCsmHta+XdYuu8GoyP+wd9I6XHcPXug2EgIH6GOqMYn5dgOd1nFf52hLkZ/xt0VSt14aC+YOeYWVdR2v4zSTa7itirSsU8W/f2FzG3ky3qscdkRkBRztDUFcUaXXv3setKpYWBfJtvZZ6bvKBuiOGhnsQ7zfF/NKqbFpDL5ZZpNdx84Dn+YhpdwW28mOChRs5t7naHJqAfvXtqRbp9e7S69AOCV+zWHYF+RFBzrsGbNJ5XiN3qrXdXumaidwBursIvSQ2VZe7/kB3J1lHOhcuhc1IJBKJRCKRSCQSiUQikUgEmsG/WvLKFoRr3LgAAAAASUVORK5CYII="
      /></a>
    </div>
    </form>
  </body>
</html>
    
    
