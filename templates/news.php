<? if(is_array($news[0])) : ?>
    <? foreach($news as $new) :  ?>

    <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
        <tr onclick="$('#neonews_<?= $new["news_id"] ?>').toggle(); $('.neopfeil_<?= $new["news_id"] ?>').toggle(); $.post('dispatch.php/news/get_news/<?= $new["news_id"] ?>', { admin_link: 'new_sem=TRUE&view=news_sem' },function(x){ });">
            <td class="printhead" height="30px;" width="2%;">
                <? if($new["usercount"]) : ?>
                <a href="#neonews_<?= $new["news_id"] ?>"> <img src="assets/images/forumgrau2.png" alt="normal" class="neopfeil_<?= $new["news_id"] ?>"><img src="assets/images/forumgraurunt2.png" alt="ausgeklappt" style="display: none;" class="neopfeil_<?= $new["news_id"] ?>"></a></td>
                <? else : ?>
                <a href="#neonews_<?= $new["news_id"] ?>"> <img src="assets/images/icons/16/red/arr_1right.png" alt="normal" class="neopfeil_<?= $new["news_id"] ?>"><img src="assets/images/icons/16/red/arr_1down.png" alt="ausgeklappt" style="display: none;" class="neopfeil_<?= $new["news_id"] ?>"></a></td>
                <? endif ?>
            <td class="printhead" width="2%;"><a href="#neonews_<?= $new["news_id"] ?>"> <div style="padding-top: 6px;"><img src="assets/images/icons/16/black/news.png" alt="news"></a></div></td>
            <td class="printhead" width="65%;"><a href="#neonews_<?= $new["news_id"] ?>" class="tree"> <div style="padding-left: 5px; padding-top: 6px;"> <?= $new["topic"] ?></a></div></td>
            <td class="printhead" width="30%;"><div style="float: right; width: 205px;"><div style="width: 100px; float: left;"><a href="about.php?username=<?= $new["username"] ?>" class="tree"> <?= $new["autor"] ?></a></div><div style="width: 75px; float: left;"><?= date("d.m.Y", $new["date"]) ?> </div>  <div style="width: 30px; float: left;"> | <?= $new["usercount"] ?></div> </div></td>
        </tr>
        <tr style="display: none;" id="neonews_<?= $new["news_id"] ?>">
            <td colspan="4" class="printcontent"><div style="padding: 10px;"><?= formatReady($new["body"]) ?><br />
            <div style="padding-top: 10px;">
            <? if(!empty($new["vls"])) : ?>
                <h4>Aus der Vorlesung:</h4>
                    <? foreach($new["vls"] as $vl) :  ?>
                        <p><a href= "seminar_main.php?auswahl=<?= $vl["id"] ?>"><?= $vl["Name"] ?></a></p>
                    <? endforeach ?>

            <? else : ?>
                <h4>Aus der Einrichtung:</h4>
                <? foreach($new["einrichtungen"] as $ein) :  ?>
                    <p><a href= "institut_main.php?cid=<?= $ein["id"] ?>"><?= $ein["Name"] ?></a></p>
                <? endforeach ?>
            <? endif ?>
            </div>
            </div>
            </td>
        </tr>

    </table>
    <? endforeach ?>
<? ELSE : ?>

<div style="" name="keineNews">Es wurden keine Ank&uuml;ndigungen gefunden</div>

<? ENDIF ?>