<?php
    session_id("EinsatzMonitor");
    session_start();

if(isset($_GET['json']))
{
    $json = file_get_contents('php://input');
    if(!empty($json))
    {
        $data = json_decode($json, true);
        
        $_SESSION = array_merge($_SESSION,$data);
    }
    if(isset($data['reset']) || isset($_SESSION['reset']))
    {
        $_SESSION = array();
    }
    echo json_encode($_SESSION);
    die();
}else{
?>
<!DOCTYPE html>
<html lang="de" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <link href="data:image/x-icon;," rel="shortcut icon" type="image/x-icon">
    <title>Zugtrupp Dashboard</title>
    <style>
        #options > * {
            margin: 0 auto;
            border-color: blue;
            border-width: 2px;
            border-style: solid;
            width: 95%; /* oder eine feste Breite */
            font-size: 2vw; /* Passt die Schriftgröße an die Breite des Viewports an */
            white-space: nowrap; /* Verhindert Zeilenumbrüche */
            overflow: hidden; /* Versteckt überfließenden Text */

        }

        #overview > * {
            margin: 0 auto;
            text-align: center;
            border-color: blue;
            border-width: 2px;
            border-style: solid;
            width: 95%; /* oder eine feste Breite */
            font-size: 5vw; /* Passt die Schriftgröße an die Breite des Viewports an */
            white-space: nowrap; /* Verhindert Zeilenumbrüche */
            overflow: hidden; /* Versteckt überfließenden Text */
        }

        body {
            font-family: Arial, sans-serif;
        }

        .settings {
            margin: 0;
            font-size: 1vw;
        }

        .widget {
            border: 1px solid #ddd;
            margin-bottom: 20px;
            padding: 10px;
        }

        #clock {
            font-size: 100px;
            text-align: center; /* Zentriert den Inhalt von #clock */
            color: red;
            background-color: black;
        }

        .bold {
            font-weight: bold; /* Macht den Text fett */
            font-size: 150px;
        }

        .unterstrichen {
            text-decoration: underline;
        }

        div.einheit {
            margin-bottom: 1px;
            margin-top: 1px;
            padding-top: 1px;
            font-size: 18px;
        }

        #gesamtstaerke {

        }

        #headDiv {


        }

        #einsatzZeit {
        }

        #einheitenListe {
            font-size: 50px;
            text-align: left;
            font-size: 50px;
            text-align: left;
        }

        .spoiler {
            cursor: pointer;
            margin-top: 20px;
        }

        .formContainer {
            display: none;
            margin-bottom: 20px;
        }

        .details {
            font-size: 2vw;
            text-align: left;
        }


        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
            /*  padding-right: 80px; /* Platz auf der rechten Seite für den Text */
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .slider-text {
            position: relative;
            display: inline-block;
            vertical-align: top; /* Zentriert den Text vertikal, bezogen auf den Slider */
            margin-left: 55px; /* Abstand zwischen Slider und Text */
            user-select: none; /* Verhindert die Textauswahl */
        }

        /* Dark Mode Styles */
        .dark-mode {
            background-color: #333; /* Dunkler Hintergrund */
            color: #fff; /* Helle Schrift */
        }

        .dark-mode input[type="text"],
        .dark-mode input[type="datetime-local"],
        .dark-mode input[type="number"],
        .dark-mode button {
            background-color: #555; /* Dunkler Hintergrund für Input-Felder und Buttons */
            color: white; /* Helle Schrift */
            border-color: #777; /* Dunklere Border */
        }

        /* Optional: Stil für den Slider im Dark Mode anpassen */
        .dark-mode .slider {
            background-color: #555;
        }

        .dark-mode .slider:before {
            background-color: #aaa;
        }


    </style>
</head>
<body id="body1">
<div id="overview">
    <div id="headDiv">Rufname: Bitte eigenen Rufnamen angeben</div>
    <div id="FüStDiv">FüSt: Bitte FüSt Bezeichnung angeben</div>
    <div id="clock">Lade Uhrzeit...</div>
    <div id="gesamtstaerke"></div>
    <div id="einsatzZeit">Einsatzzeit: Bitte Einsatzbeginn angeben</div>
<details class="details" <?php if(!isset($_GET['options'])) { ?> style="display:none"<?php } ?>>
        <summary class="details">Einheiten Erfassung</summary>
        <div id="einheitenListe"></div>
    </details>

</div>
<div id="options" <?php if(!isset($_GET['options'])) { ?> style="display:none"<?php } ?>>
    <details class="details">
        <summary class="details">Einsatzdaten</summary>
        <form class="details" id="einsatzBeginnForm">
            <label for="einsatzBeginn">Einsatzbeginn (Datum und Uhrzeit):</label>
            <input id="einsatzBeginn" name="einsatzBeginn" required type="datetime-local">
            <button type="submit">Einsatzzeit starten</button>
        </form>
        <form class="details" id="EigeneInformationen">
            <label for="eigenerFunkrufname">Eigener Funkrufname:</label>
            <input id="eigenerFunkrufname" name="eigenerFunkrufname" required type="text">
            <label for="BezeichnungFüSt">Eigene Bezeichnung für FüSt</label>
            <input id="BezeichnungFüSt" name="BezeichnungFüSt"  type="text">
            <button type="submit">Speichern</button>
        </form>
    </details>
    <details class="details">
        <summary class="details">Optionen</summary>
        <br>
        <label class="switch">
            <input id="darkmodeswitch" type="checkbox">
            <span class="slider round"></span>
            <span class="slider-text">Darkmode</span> <!-- Hinzugefügter Text hier -->
        </label>
        <br>
        <label class="switch">
            <input id="sekundenswitch" type="checkbox">
            <span class="slider round"></span>
            <span class="slider-text">Sekunden</span> <!-- Hinzugefügter Text hier -->
        </label>
        <br>
        <label class="switch">
            <input id="Uhrzeitformat" type="checkbox">
            <span class="slider round"></span>
            <span class="slider-text">Datum und Uhrzeit anstelle von DTG</span> <!-- Hinzugefügter Text hier -->
        </label>
        <br>
        <br>
        <hr>
        <!-- <p class="settings">Aktuelle Einsatzdaten exportieren</p>
        <input id="fileOutput" type="button" value="Daten speichern">
        <hr>
        <p class="settings">Zuvor gespeicherte Einsatzdaten importieren (ACHTUNG! Aktuelle Daten gehen verloren!)</p>

        <input id="loadFileXml" onclick="document.getElementById('fileInput').click();" type="button"
               value="Daten einlesen"/>
        <input id="fileInput" name="fileInput" style="display:none;" type="file"/> -->
        <hr>
        <p class="settings">Aktuellen Einsatz zurücksetzen (ACHTUNG! Aktuelle Daten gehen verloren!)</p>
        <input id="reset" type="button" value="Daten löschen">
        <hr>
        <p>Für mehr Informationen <a href="https://github.com/goerdy/FuehrungsstellenStatusMonitor">FührungsstellenStatusMonitor
            auf github.com</a></p>

    </details>
</div>
</body>


<script>
    var myData;
    //favicon
    var favIcon = "AAABAAEAICAAAAEAIACoEAAAFgAAACgAAAAgAAAAQAAAAAEAIAAAAAAAABAAACMuAAAjLgAAAAAAAAAAAAD////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////19fX/6Ojo/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////9DQ0P+RkZH/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////zc3N/4mJif/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Nzc3/iYmJ/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////87Ozv9jZGT/qaur/6mrq/+pq6v/qaur/6mrq/+pq6v/qaur/6mrq/+pq6v/qaur/6mrq/+pq6v/qaur/6mrq/+pq6v/qaur/6mrq/+pq6v/qaur/6mrq/+pq6v/qaur/6mrq/+pq6v/qaur/6mrq/+pq6v/qaur/7Gysv/w8PD/0s7O/xpkZP8Bq6v/Aqur/wKrq/8Cq6v/Aqur/wKrq/8Cq6v/Aqur/wKrq/8Cq6v/Aqur/wKrq/8Cq6v/Aqur/wKrq/8Cq6v/Aqur/wKrq/8Cq6v/Aqur/wKrq/8Cq6v/Aqur/wKrq/8Cq6v/Aqur/wKrq/8Bq6v/GmVl/9LPz//Szc3/GYmJ/wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8ZiYn/0s3N/9LNzf8ZiYn/AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///xmJif/Szc3/0s3N/xmJif8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////GYmJ/9LNzf/Szc3/GYmJ/wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8ZiYn/0s3N/9LNzf8ZiYn/AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///xmJif/Szc3/0s3N/xmJif8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////GYmJ/9LNzf/Szc3/GYmJ/wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8ZiYn/0s3N/9LNzf8ZiYn/AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///xmJif/Szc3/0s3N/xmJif8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////GYmJ/9LNzf/Szc3/GYmJ/wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8ZiYn/0s3N/9LNzf8ZiYn/AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///xmJif/Szc3/0s3N/xmJif8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////GYmJ/9LNzf/Szc3/GYmJ/wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8ZiYn/0s3N/9LNzf8ZiYn/AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///xmJif/Szc3/0s/P/xplZf8Bq6v/Aqur/wKrq/8Cq6v/Aqur/wKrq/8Cq6v/Aqur/wKrq/8Cq6v/Aqur/wKrq/8Cq6v/Aqur/wKrq/8Cq6v/Aqur/wKrq/8Cq6v/Aqur/wKrq/8Cq6v/Aqur/wKrq/8Cq6v/Aqur/wKrq/8Bq6v/GmVl/9LPz//w8PD/sbKy/6mrq/+pq6v/qaur/6mrq/+pq6v/qaur/6mrq/+pq6v/qaur/6mrq/+pq6v/qaur/6mrq/+pq6v/qaur/6mrq/+pq6v/qaur/6mrq/+pq6v/qaur/6mrq/+pq6v/qaur/6mrq/+pq6v/qaur/6mrq/+xsrL/8PDw////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=";
    var FüSt = "PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjI1NiIgaGVpZ2h0PSIyNTYiIHZpZXdib3g9IjAgMCAyNTYgMjU2Ij4KCTx0aXRsZT5Gw7xocnVuZ3NzdGVsbGU8L3RpdGxlPgoJPGRlZnM+CgkJPHN0eWxlIHR5cGU9InRleHQvY3NzIj4KCQk8IVtDREFUQVsKICAgIHRleHQgewogICAgICAgIGZvbnQtZmFtaWx5OiAnUm9ib3RvIFNsYWInOwogICAgICAgIGZvbnQtd2VpZ2h0OiBib2xkOwogICAgfQogICAgCiAgICBAZm9udC1mYWNlIHsKICAgICAgICBmb250LWZhbWlseTogJ1JvYm90byBTbGFiJzsKICAgICAgICBmb250LXdlaWdodDogYm9sZDsKICAgICAgICBmb250LXN0eWxlOiBub3JtYWw7CgogICAgICAgIHNyYzogbG9jYWwoJ1JvYm90byBTbGFiIEJvbGQnKSwgbG9jYWwoJ1JvYm90b1NsYWItQm9sZCcpLCB1cmwoImRhdGE6YXBwbGljYXRpb24vZm9udC13b2ZmO2NoYXJzZXQ9dXRmLTg7YmFzZTY0LGQwOUdSZ0FCQUFBQUFFbjhBQk1BQUFBQWVWQUFBUUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFCR1JsUk5BQUFCcUFBQUFCd0FBQUFjYmpRcWprZEVSVVlBQUFIRUFBQUFIUUFBQUI0QUp3Q2VSMUJQVXdBQUFlUUFBQVcwQUFBTmtPV3gwbDFIVTFWQ0FBQUhtQUFBQUNBQUFBQWdSSFpNZFU5VEx6SUFBQWU0QUFBQVVnQUFBR0M2YmxBOFkyMWhjQUFBQ0F3QUFBR0dBQUFDU2ttRmxCcGpkblFnQUFBSmxBQUFBREFBQUFBd0Q0RVNpV1p3WjIwQUFBbkVBQUFCc1FBQUFtVlR0QytuWjJGemNBQUFDM2dBQUFBSUFBQUFDQUFBQUJCbmJIbG1BQUFMZ0FBQU51NEFBRm1JMFBsYmgyaGxZV1FBQUVKd0FBQUFOUUFBQURZQXVOd1NhR2hsWVFBQVFxZ0FBQUFnQUFBQUpBL2FCWWxvYlhSNEFBQkN5QUFBQWU0QUFBSmdXTzBoYm14dlkyRUFBRVM0QUFBQklBQUFBVEl4V3h1aWJXRjRjQUFBUmRnQUFBQWdBQUFBSUFHMUFhNXVZVzFsQUFCRitBQUFBZ3dBQUFSc1RRdWdUSEJ2YzNRQUFFZ0VBQUFCWlFBQUFoRlM4T3h3Y0hKbGNBQUFTV3dBQUFDSUFBQUF1VThFSXpSM1pXSm1BQUJKOUFBQUFBWUFBQUFHMENaWStRQUFBQUVBQUFBQTFDU1l1Z0FBQUFERThCRXVBQUFBQU5VZmdLVjQybU5nWkdCZzRBRmlNU0JtWW1BRXd1bEF6QUxtTVFBQUNoc0F4Z0FBQUhqYW5WZHBiRlJWRlA3ZVRMZnBQcDFwNlVJSGcxaExXOVpDcFZDQkVDMkYxa1RwWmhkQU1SSnRLNVNtSlpFWXd3OGxLRlVxaVlreEpQN1FTR0ppeGFBa1JnbVNvRkVFdEFSalRVU2diQU04dGF6NlE5L3hlNmZ0TEYxbzRaMmMyM3ZQY3U4NTN6djN6U2tNQUxId29RREdJNlVWVlloQkJDVVFnYTB4bXA5dDIwQVpCbGJVT2ZnM0Fvbkdlc2RTeHhFWWhrL3RzN0NVVklvSzFPTTViRUE3dG1BcnR1RWRmSVN2Y0JFbWJ1TmZJOVpJTjlKcFk2cTFUUVlhRWNmUmEzWERpV255UE9wa0J4cWxrcXRFdVlobGNoUHI1Qy9iVHE1d3JLWE1uaC9TOFNqSE9pUXlnbG81eUhVUG1xUUZHMldESFNHOFdBWTNOWDFZQjQ5SzdxY2tucEtybENReTZ6anFiTHNrK1pHYUZKUkxON1c5ak9HSytqUnh2VkdPNlZrWG1QbHlPVVBkWHZ0VStkcU9FSFBWejdaMW9wdzcxU0dkR2JscFVZNEVqbG1JNW9sZTVDQVhlY2pIYk16QmZCUmpFVXF3R0V1STJBcGExcUlCYTlCSzFGN0hEcnlCTitsNVJIR05RaHFtWVJkMm81czRPdW5ucFdjT2ZkTzR0OXY2Q1I1eWpuVVN1WXk3Z0R4TFptTWUveGFSaTJsZEtyK2pqQm12SUs4a1Y1Q3J5RFhrZXViWklINnNJYmR5ajAzMGFhZDlCM1dicWV2azNqdkpYV1FIY21Rcldza1JLSmJUV0NUblVTSi9ZckdjWWx6RmNweVNEN25hemRVc3loZVMyOGdHYzBoZzVvVW93a1A2eHJjRU1pc2dlaFdvUVFzaUdhT2ZNZm9abzU4eCtobWZYNlVtcFNhbEpxVW1wU1lqcWVMWU1UanI0NnlQbG01NUJSNXlKM2tudVl0c1IySXlFcE9SbUJwSk9SN0hFM3hMalhnS2p0Z0NPNUtZR0ZjbXB2SUdnSHVjbHB1eVRBN0xBaklzdnpUSlc5YmYwc0lxblBERHVDSGZreThFSkRmR2NYRUhiVVAyT1c5cnhqM3RiR0IyaGZ4ejZMbWNINWJMMGlmYkpoejdPZHpGSTMvd05nWWZMKy9wQ0l0Ui9YNlFsK1JUNldhR3lWd3E4LzZDOS9CbGFpdHNHOHVTUFl4K0g4ZlBySDdwbDQrbFJIMTNTYnlzbDZmbEFHOFdCdGoyUXFRa1VWdW92a1ZTTGR1a2lldG15eWM5c2pia2JIUFVpSHBENXNmSHlQWjl1U0M5OG5hSTVQSUltd0Fla2paQzk2cGNzdjZSL2RabDY2Z1V5blY3eHdsZ3ZGSHJKMmtVelI2eVYzNlRZMVpMZUlYSkxTbWJ3TTY3eDdVNHdaMXVES3ZJZ2RrMEhhZkx5WURrR2JYUEg2em5IcmtxWi9Yck9xUy9PcUFaRmF1U1llY2VGSk9WbU1KcGl1NzI2NUNuMkVPQ1pOQ2lqNVc5Vjc2VkF0YkdJYXR2NUgzUUp6RllnVkt0NDZhUUcwTUVlT09iZ3pIeURWKzhVL1VPWmpGY2VvU3hIQWpVdzFEY3R1WkxWdU1wcmNrWDVGR1Z2Q3NuclAvNDk1T3dIYTRGWnZ2SVozQlBqM3h4MXg2M1FxdHBjSFk5UExKZ2JPSDNKMWdac3Y4dVQ3MGRNcThmejJLRUxvalY5c0NzUDh5aVAvdzlEY1VjbGtsa1FQdExZRlkzN0I1ZEM5c2pKYkNJR2lXcXl2QnFzYzhNZmtua085empFL3BsR3NQQ0hGUFRQNjd2bUJaaDM4ejN3alNEcU1zM0E2ZkxCM2Y2YWxybjdpbHJWZ1cvK1BmWko4dWxrVGZQL24wZDdiZExQZytzWGh4ejcrRjMySUVaL0IySlpnY1FUL0x4UytQR0ZIWjBIbzRQa2d6MmJybnNGS2FUbk96aThsZzdNekNUZnJOSThlenBac1BGdm00T084cTU3SE1jbUVkeXNzK2J6OTYybUJTRmhhUjRkbStMMkF1VmtKTHhNQ21SZmR4aWZ0dVhrTnpzQlV0NTVuSlNLc3BJWHF3a3BlRXgwaVNzUWlYSEtsSTZxa21wN0JwcmtjbCt1NTZkWUFNcEE2dEprOWxKcnVGOExTa2JtMGhPdEpIaTJWMjJjLzhPVWpvMms3SzAyL1Noa3pSRnUwNGZkcUtMOHlGRVhJcUlTeEh4c1A1VHVLOUhLWWVVckxoRXNvc3I0R2hqTVZremoyU25WOFJ4QVNsVDg0L1YvS00xL3lqTlAwSHpUOUg4WTBMeXoyTGVLNWw1QmNtcm1XZHJ4NVlka3I5VDg1L0V2ckdHTmsrUzBoV0ZPRVVoUWxGSVV4UWlGSVZVZHRhdGpOYkdJbEt4aUZZc3NoUUxwMklSaCsxNGpkblppTGdVRVk4aTRsSkVQRVRFclloQUVURVVFWWNpNFNRU2VUeHBCdCsyaTdrdVlWYWx6Q0pEMzk3a3dSeFdNV0tmdnJHcGpMU0IvNG1zWm1RUE1LNDJZdGpPQ1BKNWRoZG0vZytkZkkvY0FBRUFBQUFLQUJ3QUhnQUJSRVpNVkFBSUFBUUFBQUFBLy84QUFBQUFBQUI0Mm1OZ1ptRm0yc1BBeXNEQ09vdlZtSUdCVVI1Q00xOWtTR05pUUFZUEdKaitCekFvUkFPWkNpQitRV1ZSTVpERnBQcUhMZTFmR2dNRFJ4SlRud0lENDN4L1JnWUdGaXZXRFdCMVRBQ0NNdzZvQUFCNDJtTmdZR0JtZ0dBWkJrWWd5Y0RvQXVReGd2a3NqQnBBMm8zQmdZR1ZnWTJCaVlHVGdaZWhqbUVCdzJLR3BRd3JHVll6ckdQWXdyQ0RZVGZERVlackRIY1k3ak04WWZqRzhJZnBHTk10cGpzS1hBb2lDbElLY2dwcUN2b0tWZ3J4Q21zVWxWVC8vUDhQTkE5aWpnTFFuRVZBYzFZQXpWbUx4eHdHQlFFRkNRVVpzRG1XU09Zdy92LzkvK3YvcC8rUC9ELzgvOUQvQS8vMy85LzNmK2YvYmYrMy9GL3pmL2IvYWYrbi9KL3d2LzUvMWQrTmYrZjluZnVnNmtIcGc2SUhoUSt5SGlROWlIeGdldi9sL1pKYnN5SCtwUVpnWkFOaWNDQUNhUmF3SDlFVU1EQ3dzckZ6Y0hKeDgvRHk4UXNJQ2dtTGlJcUpTMGhLU2N2SXlza3JLQ29wcTZpcXFXdG9hbW5yNk9ycEd4Z2FHWnVZbXBsYldGcFoyOWphMlRzNE9qbTd1THE1ZTNoNmVmdjQrdmtIQkFZRmg0U0doVWRFUmtYSHhNYkZKeVFtSlRNVWdPd3BMQUlTSldpV2w0S0lNZ2FHMURRZ1haeVRPUzAzQXlxVGp1bVZyUHdKS1dCR1IyZHZYMWMzbURsMTBtUVExZE9QcWJ5OEVraFU1QUVKQUY5eWhCUUFBQUFBQkRvRnNBRGhBTEFCSkFEWkFPQUJDQUVYQVI0QkpBSXZBTFVBNHdEbEFMc0EzZ0VpQU9nQTd3QytBRVFGRVhqYVhWRzdUbHRCRU4wTkR3T0J4TmdnT2RvVXM1bVF4bnVoQlFuRTFZMWlaRHVGNVFocE4zS1JpM0VCSDBDQlJBM2FyeG1nb2FSSW13WWhGMGg4UWo0aEVqTnJpS0kwT3p1emM4NlpNMHZLa2FwMzZXdlBVK2Nra01MZEJzMDIvVTVJdGJNQTk2VHI2NDJNdElNSFdteG05TXAxKy80TEJwdlJsRHRxQU9VOWJ5a1BHVTA3Z1ZxMHAvN1IvQXFHKy93Zjh6c1l0RFRUOU5RNkNla2hCT2FiY1V1RDd4bk51c3NQK29MVjRXSXdNS1NZcHVJdVA2WlMvcmMwNTJyTHNMV1IwYnlETXhINXlUUkFVMnR0QkpyKzFDSFY4M0VVUzVETHByRTJtSml5L2lRVHdZWEpkRlZUdGN6NDJzRmRzclBvWUlNcXpZRUgyTU5XZVF3ZURnOG1GTkszSk1vc0RSSDJZcXZFQ0JHVEhBbzU1ZHpKL3FSQStVZ1N4cnhKU2p2amhyVUd4cEhYd0tBMlQ3UC9QSnROYlc4ZHd2aFpITUYzdnhsTE92aklodG9ZRVdJN1lpbUFDVVJDUmxYNWhoclB2U3dHNUZMN3owQ1VnT1h4ajMrZENMVHUyRVE4bDdWMURqRldDSHArMjl6eXk0cTdWcm5PaTBKM2I2cHFxTklwemZ0ZXpyN0hBNTRlQzhOQlk4R2J6L3YrU29INlBDeXVOR2dPQkVONk4zci9vclhxaUt1OEZ6NnlKOU8vc1ZvQUFBQUFBUUFCLy84QUQzamFwWHdKZkJSRkZuZFZkMC9QZmZTY3lTU1paRElUa2hEQ2tKbUVNQndDZ3JBZ04zSWZJZ2lpaUlvZ0lwNklyaGNxeThyaUNvcnVxcXZMdW5RUDQ3RmVJT3A2MzI3dzVGRFozWWdIS3A2UTRudXZxaWNIc3U1KzN3ZS9tZW5wNlhUWHUvL3YxYXNpRWhsS2lIUzZaUktSaVpYME5DaEo5YzlabGZEbmFVTzFmTkEvSjB0d1NBd1pUMXZ3ZE02cVJvNzB6MUU4bjlIaVdsVmNpdytWS2xpUzNzb1dXaWI5dEdXbzhncUJXNUlMQ2FFYkxUcS9iMitTZzNOMWVTcVRHcVV1WjVGSUhkVnRLWjIwNkVyYWtDMnR1cG8yckpaV3cwN3JpR0dobWw5WHNyMGE4UDRVWGhmU0pQdUFKaWs1U3VoMzhxTkhodUg5MXloZTZVNjFpQ2pFVHZvVG5hUjBTd1lmWUZQcTRHNVVkK0R0RFZsdHpTc3FjY0pKMlFkM3JqTnNjTWJPenhoT2VGcXZob0NXMGF3WmZGdno2dWkvdnpJYTdtdG5CMy9rYi9DY2FrS1UxVUJIQ1NtbkMwZ3VDblRrUXVIaVRDYVRzd0lkT1p2VEJjZDVRcU5XZDkwMlNTc3RTMFl5QmxGYXR3VWpSU1hKU0RwdlVmaFBzaTlXamo5WjRDZlY3bkREVDFTdlNPblJsbnl4R0dHeHp3alR1bnlJZjRPSE9PcTJEUW9GN0hYYmJLR3dyUzV2RlZkWlUzbWJ1TUpxd3l1c2lyMU9EL21RbXJ4TEVCYW5kWHJ2NktNRGJZYytKYUU2eDZNRDdZY080b0VlOVcyVG90WUFESWEvcS9nT2o5MW1MN2JCUWRpM3pSRjJCdkJ1Mjl3aEYxemc0KzhhZncvaU8xNFQ0ZGZBWHhYeHY0SjdsaFR1VTFxNFR4bGVzeTFXdUxJY3o4dURmSktNbFBzMFpFMXBXYXk4NXpILzlFRlJsRWhUUEpDQVYwYk80Q3VVNEs5RUFGL05tVUNpbXBMTXY2aGw1TU1qZG94NFpPUUhQMlUrT3ZxcmgzKzE0MWVQL09vZk96STc2RzMzMDhvdDlFNDJCMTliMk83NzJRSjZHNzdnUEtnaG9hVHBhSGRGVTI4bDlXUUQwYnVuOGhhWk9JQ3pwYW04bng5UnZXZEs5N1FZNWFDWnBLSkZNeVJIcTE3dU16UmdjVkJJb1R1Y3p0ZUs0NkRQc0FISGk5TkdGZnhCSkcya1FNOGtEMml4SzJ2VWFwcC9tOU5TV3BtTVpJMHFHNXhNWkVISHUydCtvNll1bTlWTHRRY0o5VVNLcXVCMzNlODN0R0FXVkQvUUhBK0dNK25lVFkzZHFudlNwc2JlelUyWlVJeDZhYnl4VzZKU0RRWERrUmdOQlZWcktOSFVrMllsV25ySjdCbW56VnF5dTJYWFE1dTN2aURKN0pPNUV5ZFBtbmJoaHkyN0h0NzgyQ0g2bE9YWHkwNmJNSDVCai9IUFBYamZyc0N1OTZKZlBXVlpmc1c4Q1dOT1MwMzVlKzZCTndKL2Z6YndNYkdRaHFPZldlWlpuaVZ1MUhqZ1VEOXlCOG5WZ2RZYkRVcHJUZ0dWTjRxVTFueWZaSjNpcmpQNndLSG01SWVhMGtyMS9taDFlWTlLVGdDK2VIeEdFRGdHMnRvRTMydytvd0srOVJTLzlmUVpqZkN0aG44ekJnRERnaDVrbEZKYWpveHE3QWxmeXBKMU1maENqSVk2WUZ0NVZ1K2o2YVZab3dnNHFzZXl1cUxwWmNncTRFNG1IUzZqUVRWUjJhMlpzMjBBRld3SzBBaXQrb1hmRy82MGZzTzk5NjcvM1gwN0IvWHZPMmh3djM0REIwdlAzZFNXcGRYM3JmL2RuK0NIKzNlZTFHZHduMEg5K2c5VVRoNjk2aTlicmh5OTZzOS9YdFYzN05pK28vdU9IOS8zU0lWeS9zbUhiOW95K29xL2JGazErc28vYjFtVkhYL3l3TkhaOGVPekJEeGYvZEVERmhmd01rNTZrQ3k1aGVSSzBIdVVJek9yNU5hY0hablpMQVBiK25KblZRbmFVK25UNjFIalZCczR4WlJSYjhOVHlDckRaVzNWWFp5alJraHROZnJCWjZVS25KQ0JXL1dhUDI4dnI2cjFvUks1dEp4V1dwTUYzUXI2OVpLc0h0TDBJbUJpVlFsY1hKclZtMEhoVkZkUmJTKzRGcmdIcXNVVkRGZ0UrdVNsOUFUYWxBSEZpbGpqMVI2YXFFeFdjWTQxVTZ1SEJrRHRtdUYzenJ6Nks2OGVPdVNkQnpjL04zZlNMQnBNMXJ3M2ROanJFbnQ4OUtUOEdlOHhScFBuVEdzNnE0RzlIY2hVblZaWE43bFA5WWgrZzRmUmE1ZHZtVFh6MXJFUHZQYnNtcmwvR0hBaWU3TC9takhYLzN2NkljdTA1dWI5ejAxYldsSkNaN3A3elpDdXFadlIzQ2M1cWo0OVpnclk2dzN5UVRxQisvb2E5UFNtbTZlNnBlRGpPL2w1UTIzMzZ2SU56dzJURDBvMTdCdTJuOXY5TkhhQ2RKVTZpbWdrUUtqdTUzL3RzTFVhQWZFblRUN1NuSW1va2hieStTUFdSRGN5amZiYW9UYXR6dVZXTjZrNzJPdFMzekM5VmtxTVh6ZVhqV0ovamJJSDJPajVONCtYS3ZtOUI4QzlMK3g4YjduRmNIZmN1N2RmYS9KSjFjMlpNSUhiRTJ0aXdLcjhRNWYzVm5mUy91eVpuU3A3WlM1OWlFNk0wbkgwYi9OdUd0ZjJKcnN5ekZhMXZUV08zN3RTR2lKZkFESElBM2VIa0l6UjA1c3lmT0xPelJZNUkxZEZMQUdyazFZSEtyTzAxdjk0a0NiNnNGZWYzN0QxZ1Z0ZlY4cU5pK2tNdG5sRmZocjcvaHhheWY1MUZ2VUpQOWhBMWlrUjVTSGlKSk40QkxYeUNOb0E1bWxKNXdqRjZFSWM5cm9jSlhoSVpUczgySlhTSFMyNmxEYnM5bGFJM2ptN0EzK3pXK0V5aHgwUEhjUmVaN2hOamdJNjBPSWhmRytnWjlFUVBZUDluaDJRZWgyaDE3UGxSOWhjZWdjZlIxOTJDLzBOK1FZaWVSWFI3YW04QXFBQjVldmdYQVJUd09CbXFESW9zRDJMOXoyQkRxQnhLMmhwMytydVZLVkxKN0tQaHEyZitkN0xDejRaZ2ZkYlRabjBzTFFITExBYzZUS28zSW92cWlzcGc4Q2RaRmNyWWdFeHd0QnFxWkd5ZmZ2ZzcxYkJIOTlOMFhKVFhSRkxwMk84Q2RjNmdDdjRFdmRCcUxLS0poQ2lFSHEwOWVocWFSZkhQaUF2eXVVRlR4WlhXbW1HUnFUd08yMmZYcXJXLy9nV3gwcW5IVDJnOURWOWJsK1NjNktEME9TQ3Q4V0JsL0puZXNCQkNLZHEyT0RSWmFiWE5KeEtsck5GODVGTW1oU0RlQk9WaElvQW9nbFBkeHExZi93UmRiTHZQL3FFZlVjVFo1dytkOEhDdWZNV1NNMTBNSzJnOWV3dHRwZHRaM3ZZUDJnZHRlaDMzcW5UaXgrNDY4Ni9DajA1QVZneUR1aXhrOUVrcCtMb3JBb1h2b1FEcEpLdllrY0tJUStYbUkzekppZHpjQ0piUUM5c01oN2FVQzhRY2huRXl0MFdjcjlLaTRUaVRjM2FDVkxwZDBlKytrNzUwNS9tSGk2TGJkMGFrOStENTlZRDlxb0R2cFNTbTBuT2g4OTFBbGZ3SU8rV2lRc0VJNXNjeWhYeFp4U1Zvb3FXcGZUU0ZxTUVZbmFKendpaDVzQjRRaXFIVXo0QVM2cFA5NkNQZFlOSGRhY01qN1hWaUtGWEJRZVprNTBPOUp4dURXSTNNWncrR0tuR3Z6cXllcEVmaUE5bGVYenVCcHhXTUFEVFNMd1pwTjlFQzM0eEZFOWJxUHVUZlZUNnpYMnNUYUlyWGJ1UHhPaXVCWlBuelpoL0p2dEUvdkpacW4zNjlGVjc4MnkvWlMxYnU1MytlTUZkSjgyODRMcnpCYThiUVJkcWdPYnVaQ1hKMVNETkZpRFBVb09qdDFEZ1ppbVNISVJ6d1ZJOEZ5eXlBemRjTmFVUWd0Mm9LblZjQkloRU5DUVNNRXhPSzhjck5SY0lvQWNRV280b3BCTEljMWxBZVVpVm9MY1M0NFZSWEg0TS9LaURjTUFQT0duVjNaSW0zUWcvRkFnUGdEMUtWc3ljZjhIUzgrNitlK0hwcDU4NjRUeTJYMUtwLzZXUHFIdkZtUmRkemo3OCtDWDJJZjIxZXNyaTgrWXNYZlQrNHRPbW5qVm5qTHBsOXp2UG43cWxWMTF1K2ZQN2Q2RU5KRUhXbzdpUEM1QzVKT2ZnMGdZdHM2VzVCZVU5WGdkMUE0clBHQjQ0YXdIc0hFenByaGJkeHpNSGNFZzVxNHZEWVR0SUdGVFRaY1Z2TG5CZnFBTEU4Q0llSTFtZGFyb2ZLYVJ4RFZCbGlLdGZVeHpTbUdwclVwcEZ1OTF6ejhhMmV6ZTEvWlhLZEt4YVJBL0l6eDQ1WitmTnFKSTM3NVRjVW5kdUR5QWpMNHcxVHFZVEVjNVJSajRVVE1nOHlBZGs0bGJxOHNYT2NoOUlwaGdsVThrbFV3cVdtd0FwbElJVU9Ob0ROUU01NUluazhaWmkvQzdXOURDWFFWVkJCc0I2YTFVZ3JpV3E0L0lKdExtM1B4U1VnUFZESkZwMHpyUUZjeGVlTVhGYWdMRlQ1R0g1S0Z0UlczditTVy8rcysyTDU5bEg5TmNXejJtTDF5Mjc3TllUbWpTNWp1WG5NajFXVFdWMjVOOXZzay9mNTc1bkJ0QXlCdlF0Q3BuT3VTUlhoTlRFQ3I0SHpDM3ZUeFlodlBNakJUWGNEWldnRzBxamNjWFJiSUNlV3ZpTWwzRDg1aTlDR2tLYVliTWlhY2tZc0QwRXlGWXpQRjVRTktkZnQzSG9aczBLaHhWUGh5UFduaFJKakp4QU0ybWdyWlAzRXJUUG9NTXBuWEREc016QXMwNjY4bllxTjJ5WTlmUnU5c1c3LzJTN2FlTFNoZE1YbnpOei9QbmxVcFlTMmtTbmxNZitFWTQ4K1RkTHBpODdzT3NENEVLV2VoNTQ5SjRMTHZwZFkzMGg3aEdsTjllMWlTUm5RMHlHS29ibUkzU05jT2VDUVpaN1hTdjN1Z0R4VWRNS1dSY0VRNHpBaGczMXlnMlV1a25CcTBIRWMxRFpHbStPMHVhNHRVR3hmTHFseXNNKzhGVHVmYnJ0cUZNWlNKZnNZMSt3YnN5NDVSWTZXQ3JldHhZanpoQ1FRd0xrRUNFSmlENDNrVndJSlZGU2dJbjFJSW5xZU1nT2txaEdTZlRpUXl2QzFCbXRQQWtIUlQ3dXh2eHc2UGZwTGp6ZEhZNjdwOERPVzQwRytLbkl4SXZka3lBcWUwazhoRkRiaFJMeUFnRW9RTU5QUUVqVm11R1Y0YlBlYjdqVWJGZVUyTVg0TzJ0b29OUHhrS3VYTFRpZnZmNDZKY3ZtTC9zMSsrTEQzZXh6bWp4djFvd2xTMmZNT0s5eSt2aXhNMmFOSFRlTjNyVDh3WWJVZll0My91TWZPeGZmbDJwNDZJSVgzMzEzOStsTGw4MmJ1M3k1VkRaajBhSVpVeGVkU2JpZURnVCtsSmw2dXRqMER4Z2plUVFBTkowdmlqbGt0eGt1Ty9TMGhJZExQUzdxQzZpbkpTQW53KzFCZUt4dGMyaHlFVTh4TkFjWG8xNms2WjZzSHVNNlc2V0J3MERpTzZKcXQrcm1HT1VKQkdKanljVEdndWlCTlBUMkhocG1MMzB6NnE2aDhTR1hqYjFnYmNPMVkxNytOMDNPbXoxaWNjVjVjNmFlS3oveElhMWpyN0JQMlROc1AvdDdlZXl0NHNpMmJlR0JnNm56d0ZXYm1udHV1dlBlQjBBWFpvQ1NYcVprNFVnaE5rUUVuYkdJZ3ZxcVVqaGhNUUdKblZkU0xLS1NZdVVmaHFNZGxzU0x3ZFhOb0Fsd3doeWZ5Rm1PVWxEblNnRjNYUXJQSWNRQnR0QkljaTY4cmRPOExXaS8wbUpZSVFOeHAzV3JEN3hVcSs3a3NCUENQM0RMa2VWUGFNZGlNanlzMU1SakUrQjU3WkJNdVVmZ0lxRGtKSG1EWWlGV1FnSk5WTE9FTEZwZmVWYmJEbWtRelg5TXoyRUhYMU5mWWdlRnZDK21vK1REOGw1ZVA0b1dVQnlIVVNoZ1c0clhpZ1NBby9DNldMN2l5Qlh5RlhUVS92MTA2Lzc5d3M0WGt2N3lhL0lSZkY2elZnVmdnNFlXU2lQYUhwYlA2RTg5YjZpdlVqZGI5ekU4QzF5NC9BYlhyUmo4RGMvUzhxVXk2VnNBR0dIUXN6QUhHT0VvQW96eWxGN1NBbHd4WXNBU1YxcVArUkRtSTh6QUZOZUljU0NoaFJGSUJEaHlVRGx3TmNLbDhBTWtYeWFFS0lSU0R3M0Z3YTBYQUlRMWpnUlZ5bFIrZlM4NXV2NjhBZDFBZE9NdkdqN3B0T2tqejZEc0V4cW4zWlRzanBjL2Z2cnlkMGFrVHAyNDVzcFpmWDg5Y2ViQ3FaTU92L0xUVDhpN05PdW52bVc1bm1USUVOQ2RuQjNwaVNta0Z1anhvdDAwUXdSdFNCbmQ0V053eWdqQlJ6S1ZseFV5Q01VK2xOdE90ZHFxVi91TXZtZ3dhcXZwNi9PcVNMRk9nclBWYUVZVTBreTlyemJJTG50RFNhMTdROU5nZFA0bC9weXIvZ1NrUHE3bDdVWFJXRTg4cS9vTmF5V0doTzROb0R3OXM3cGQwK3V6Um5OTTFFbENnK0YreFZIT0dUK0pWeEJabGREZTBNNjZRY2pMWUp6SXlDbzNPSEdCQk1HamQzTUVycWdnVlB6UTJMc3B3SVBKRUQ5OWoxNU5WOU5OUHZzWG40ODV2NmpINnFrM2JnZ1VmM0gvc2pXVFpFdkcyajI1OUFhYmo3M0dubUt2c3cxZVA0U1k5S1FIQjNjYjh0bzh0b3hObERZN3NvUDZ6RWpTSHBGVTBibkw2RzY2UmFLc2hNMThqejB6ZGV5RUNWODh3Mmk2c2JmUzl2VHM0ZS85aWM2bE43REo3RFoyTzF0UUUvdHQ5eFQ5bWk2OWZIWFRNTWttMFlOVzIxTkNwMnNKc1d5QXVPTUZqRE5ZV0RYa1hBWlZXbk9xM1pOT3A3bTY1VjArZ3A3TWhZb2VUQW40NHFMQUhzbVN6Um95WWxLRU1KbW1abEFGRG1DcTQ5WklLQkNYRTdVTDZXT0E0QjlaeUh6ekI3R2FRZk1yNkppNzFhMWU1ZXZESHUvV3JmN25uL2R2bGVaSVZOaC9IeGhQQ01ZVEFJUmRoVEVuUUV4c3lROXdYRmJUdllvRFJXbk5sNVVHck82NmZKbE0wcWd1M1ZLNjFHSUV3UkRLMGxnSkt3TGQ4TUUzbjA5M1lBaUt3M0U4WlRqQU5xcmhKMGVSR1M2RFdPV1ZzUDZsKzdKNkdYZStWZHhZRkJGR2dUeXR1aWtUcitnVWNhcmlJc0xFbXdyZ3RNL0dqUnZwWjVUU2lrVUxWcTltbjdXeGI2aDcyY3FGNTdFUDM3emdzc3N2ZTM1clRBbkY5T2VmbTcrNXB2U1JpOTdZdFhmK3ZFVkxYcHd6Yy82Y0RyeTlEMnkvbkV3bHViSUMvWXFKQjNpRkZzeEJRempOaWRQdGFhTlk1WDRXMFE4V293eWxEUFU2V0FZVVJSRkw1MnhGeGFqK1FBbUhPVTBJYzVRSTREZlVUYW1NQmtHZGV3K2tHVTFWUUhjYjZUSmFmVC8xVFhzM1ZyZDYycE92dlB6bzVKdXJZKzlPWlYvZXo5NW4xMHZiRzhERjNucnhmTFozWjEyYWZiWTdzNXQ5bXE3YnlmWXV1SWorRHZVSzVkaUQ0NWtpTW9Ia1BFaUZGNmp3ZEpGaVVTY3BVcjJZeTgwTGRIbUY5d0wzYmtRNVVoYVNNWlFpN1JoSjhOSkZ2Tm9zb0puTVAwU2RkRHE3bDMzNytsTzM1M0szUHlVWXpyYXdiNzlpaDlnRGU5L2U4czM5Ynd0ZXd6aVZSMkNjUWJKSmFML2hnekdSd2loNWxQUEx2TGF0bXZDVEgrVHRuVTZpRHJxOFJDMVlTSWdUZ2lybmhJVFVoMTVhd3BJNFNZTU9ZcFRRL1drQW82MTZJSTNsZGNOdTVuUTJqYU1BQWdITnBOZDVETDJJdDBYMUFqSThwRlgrZUsrYjlhU2Zzd0J0Y2U4MkNWM3lWN1p1SDV2elYzWitPNDNiZ1VhTlhFM2FTWU1zaGM5NkdBN001WkFpbTZESVVxREk0eVFXb01pREZQbmJSZU5JNTd3U1V1VFY3UGp1aHZ4UDhuSVNDVTl6VUhnY21GcEZwY253RkloeGRDR21nNVpJQ0NoUnJnRkNxaWhqMHFZT0tzN2R4ODZMYlVVYVVtQVRYL0s2NVhsbWZ0UFpKdkxGMFhLbFUwN1RZUjRSVEdzQUlrZDRaaG9wQm9pTW1VNEViQ1RuVnNxenZOeHRPSGhpRUN6bjVVakRIZFc2cEFUSHQ1WG1KcVNCVzB1S0xxYmR0dENTMmUvVTFxeWV0UDN0MTUrWnVyajhkZnJ0Y0hid2RmWXV1MG5LcGNHWDM3WjRGbnYvNmNwYTlzOVBHbmV6NzJQQjczMnZ0TDB4YkFtOVFjaEl0WUdNeXNoZHBoY09DUzhjakpTaUYwYXA2TzRNQ2dheVNnMU81YU4yTHA4bzBoemo4a0YwQ2NscENaZFBpUlhFVVFyNlY5SlovMHFFeDNDa2pRQndKVkNFdndWQWtub3duU3NLOElJRjVPUjZPRzJVbyt5aTNNY0h3TWNyZGppUy9ka08rWVdzZ0s1UUtUTndKR29tcGl5bEdadWtDWnMyMGNTbVRadmE3b0hNMVJTcEhtTXo2ZDM0ZWZnQXBxOTByUkF2ME44TVRtTWQwTzhrd3p2cHFDUGRybzdnM1hpbEQ4aTBXekFES2RUNDhDTEozb2xHczlMWE1jcElxSG5USmhoVCt5anc2ZkRNQk9qVWpmRE1JckxBakRPOHJoRm9yMnM0dVgwb21GcHk3eFJwNGRNdWtsQndzTytjajJkZVBnK1dsTGlkMnpDN2oySVNKb0VTQlVDckhGalI4RWE2MURCd1lNZzF6RmlxdXpVMUppUmFlczZzOFVzMjBlMmJOckhQMy96azZpdm9YZXJTTXk5ZExOZmpnSSs4OWRiMzV5NTYyZGJ1c3l5L2gzR1hrc3RNWFFrS1hRbUVTMzZ1SzE3VUZYc3g2Z282clFaRmxLT0FqMUdoTGxIT3lpaXlzZ1JZR2UzTXloZ3FRVEZYQWhBOTRpTU5Zd2hFL0s2S2tLSDgweTVpUDAyWWVqRHhsc3RwMFdScDhCM3NOcnB1WGR2MjZlemZmN3lsWFJjdXBodGN1bDdDS3R1ZUt0NjYxVVh2WVl1NVhOQmZYYzR4eVZKVEY1dy84OG51cnM0S1FpRFZmWndxQ0JvZ09DVENhVWZmNU96c201dysxQTVlWDlPUU5HZkJON2xSdXgzWlRxNFc1VlB3c2pRSjJyelJzNmRkZ2RqRDdNeUNMS3pUWUt3eGNsOGhma1F5UWhwZWYxbTdOQnhjR2dETU9WS1hSTWtsYW1zdE1EK0dJNDEySG1tcENJRWV0RkpQdTVWQzBoK0M3Q2VVeW5sQ2VOTGpoSlBlTk1mNHBlaTJpaEhmYjdNNklxTG00ZGZEb0lJS1VMbk5aZzhYOC9tVkFvRTBRK01GeTIwS1ZFTXFaZzJFdU53MjBrdnBVcFV1bDZKb3Z4dExWYmJ5R1hhSldtb2EwT0hYbElhQ0NlK1hWdFBETHlwcDJyWnFQemZsVHI0c1FxNHg5VlBqSEFHdklyamg0dHd3bktDYVZDL2kvQWhoUFpUekloUkJYb1E2OHlMRWd5YTY5SnpHTFYzendqVjJqYnNBNEFCb2FKMFJRb096Y2gzMW16U0RUUWF5bldWS213dVdGNmRXemFRMUtZMVNoZUY5MkpaWFRScVA5S0N5aFlzNkpyOU5qL3drYUpPd0ptSlo5UDlSRjFlY29pNk9NY1VFTDVBcFlFRHhBNENCOUVJS0RhRm4wV3BhUTg5bDY5ZzdFRDdXMCtUREQvL3QwY2NmZWVSQjZaVXdQWUhleldheW5XRzJnODJtZjZDRC9zbitSWXYvV2ZRUmpiSi9mMElLMk12RzQzMlVYTlExNG5Pd0ZlMk1uVDErWWkxRStaTDJLTy92SE9WL0h0OFJ0Q0I4THUwYzM2Titub0ZqMk93TVdkSUVZSElkQlc3TG5SR3lGUDJKeHRtZW43N2J0T203bFpkZXV2STdNK29mZmYwTnhwNkliWlcrdXZxTU02OHplZTYzM0E0OEQwRDBIMFZ5R3RJVEtmQTgxaEh5RWZFRDNJK2FQTWM0SHcyQ0ZuZ1VEZU04b0N1bkcrTThCbi9kbWUwa2hhQmt0VmlyUk5aMmZGbGNkekh0L1NyN1kvWFk2djhza1NmWjMrYzl0Zm1BelhFOHNaaHltUXB5cVNSMVpMTnBHV0Z1R1VZb25qYUZVOWRaT09VSkxweHlwTEVIRjA0RjBKaEk1eXE0Y0NvcVVUZ1ZuWVZUd2VuWHE5TWNBRUZvMUxoWDBjSmdKZlZvUTFFejJTbEgrNUI4VWVSTVFzTlVwKzY0MGpOVEhRQTk0WElhZ3NRWGNYWjE0bGh4MG05K29OSGw1NjlhMHExSGNzUE55NWFjOGVXbVRTK3VYSG5SOHRlRVhEOSsvcHcvcExzWnEwZjJhckpZOU50V1RqenJJcGxkRWRzcTkxdzRkKzRGd20vZ25OTm15ek9RazU1blZuM2poV29haDlYZGVWR3BKSTBhYXBSWmVacVFOTXU5ZGZEcGhXekhpR0grbjlUeUxuOVJ2RnI0d0p3OW9HWlI4SEZNSDBwd09ub2JzWVo0WlZ2MjZ5b3ZxQ1diT3lCZWV6TURGaFNydFV5RUY5aXc4YUVIUFkzMitQZytHcHo5ZnJkdTZ5YXNXSDNkZlYvOXlONzUrZ0hXT3V1ajhoNGJacTI0OU1MMXJJMTlwNlNNZmZLQ1NhemwrYUxrL0hFalQyMGE4c0cyeDk0NTV6VGE2N1ZrYXU2RVV4YlVuL3dxMGx3RE90R1h4N3J6Zmo3RGdGYUo4NTZGQ2pEdkdSS0JEdFRkS3pqZ0toU0IyK2NhT2swMFlKRk13ZW5GUXNBamtGRVlWbGRXRklVNUJEZWhtN1hHNHQvcllvYzJ0ZDI5aVgzclVuclFxOWdsZnp6TTBNc3IwaDlock5YZyt6N25mVWNyU0M1Q3pOd0JCNmY3ZUg5Unp1TDFwOUdubDJJUGtSZ1ZWejhyenJsRithaWlSSXdLd3pBWWJDN281bE5HZnJqQUhjUkROL2gyN2kvZE9DZUhoVkFDaVlNUktPRmpOZ056QjR6Q0wyakU4SVdINlkzWFhMY0pBU2g3Z3YzRURyUHRGZ0pPdk0yMjl1YWJmaVA5RU9QSDM3THZxT05iNlFjK2p3MzgzOGg5WlE4eG55Zm9jUXA2WkllYjArTlBpVlNHWUtMRzlRVWZUQ09aWm9GN2FKeFdTc0Z6cWVSWE5TcWZJd1ZYc29uc2JUWUJIeDQ4c21IY0dIbFJVTmQ5aDMzS1FYZ21wUFRXMy9INGVBWEpoWkdQL2xBbXd4K2NzOXJjbVl6NWNBN2RNRUJpZk1QdXFRR1BmVm5HbTZhc1BUMjZ1Z01peW84V1BiVGowWjJqdnlUOHZCL09CM1lZZGkrY3QrMlF5VGJWSGdoaUZ4TjlXTFhhN1A1QU1QU3p2cWFRTlJBQktOQWNLRkNTZ0ZlWlZIVDFuMVNxN2FJQjlkNnJwY2dLOEhwZnNXOVY5aTE4N0VLeUlrYytvdkkvajBTcEhJdm91dmV3VjM3cXlFRGxLK1JwSEhoNkNkZVR5Y2ZpMHlnaUl0WEVweFk0WlRPSkJJMnh0Q0RpdExsYTlXaWFLNEFOa1BNMlNiWVg4M0k0OXVBWmppSXpuTnNGNDB1NjRFNG5mbHhNVjdIclZrcXBDL2Q5ZElIVXNKSmRSNjliMWZiMHNrOFBYTEMxU0lwSTltSmRkN09OZEw1YjE0dmJ2bS83bEVQUDg5aGFGODh6a3pEMnNURDJFRG5WakRSK3NFZXZzRUhkYmtyRzV1U0REcWYwQUEvMU9ROVBHancrME9JT3RNYVRUMDlBOHo4b1cxU2JYZUprRUl5U2xvSUtCVEltMjAwckJEQlNmZUZINmtmTHBlcEwyREpNbnRqRnlHMk5WZExkR2loVFc1Qzl3bE9uRTZVUGNid2pBU3R2aHZHNjI3R3lIZWRuU0FFYWlRS0ZqSDVVTGN4K1U5MVQ0TFlDUGtUaGpYcll6R040NFZPeGNCM1huWm9oMmNHWHVnQkdXZDNjZjlweFBvbG1SZHVEalF1Q3hxMkpRRHhFQVNuTEk2Vy9zNGJJbnJBU2JtdW1oNHQyaDI5Vm1uWDZtM1dIZCtyc1BPeVpZYnJVWDkxSXJKRHRpZUF0Z1grenR0ZlBzVS9MNmlJZThISlMyckJZV2d2ZjVIU2h0SzdGUXdrdEU3cUJHamZleUhUcmdYZCtmUFlkSGp0Y1IxZkxhd3I5RGFScmYwTWdUdU11V2lITllYc3RPdTl2b01USmRQb0VIOHNnMGI5aUtHSXN4QnlMdFFVZW0xZkZBRlNmUVNHYzBoUWNGUVpsTGRUN0l4a3RnVjBsenB0dnBqazJab1hhNzUwZlF2aU1ldWxkZVFydlVXanZ1WENrQ2kyZVlISUp1VHBobFNFcXYwM3IvbldQVGJYK1FYcFgydFZXUzIrdnJHVHpSRXljZVhTWS9DbXZqRVpOK2NwbUN0U0p3RXc4Rko4cDMzVGsvRWY1M3d4U0hwZjZXLzVPRkZKT2VEOEVkcStVZCtwT2NyVVdXcEthNDRIRUlJbjJmdVl6eXc2VmZTZHd5a2lJdzRPVk1TUkJlcElOSkJmSHAxYURCbUdqcVZFTWo5Y29ITmpnakkxRGNKdkxYcGZ2SWNjMWdDbzlzSkNYNG85SmlwbUlKUGg2RmJzYThLZzdSWlRDSTNZdjBEV2M5Y3M1dFRpQ2tKQm1sTVJReTRxam9wbXVHcHZFOUI1K0kwU3lXWGdjbkExbWRRMW53L2owWHo5emNzSkxFODA0VFJzT2FSeWp4SHRTVWFJdlRJWEJOU01QZmJaKyt0VDBtS0xBQjNUY2F5dGZPRXpsYVd5cnkyOXBYRDluODJNME1YemdpV2ZIMXMrYVErdHZ2ZmVzTVpNanZtVUhlNDk4OWlFMnVMaEgwTFcxSkZ2V2YrUURkemFma0syN1p3cm4wZEZQZ1VlVDFDS09TU0Z5K3pGeXVBdkFGS2ZDRUpqeUtTSkY0Rk8vd0tkU091Zm5ZYzZQY2RCRXF3NFRyUWJSTi9peWVsUXpWSUZVeFFsRGpmSFNHWVFwUFFERXk2QnVmR0phNDdQdTNhcXRDWTJhazRCaXZpWk1uMHl0bnZUcU8rKzhmT3FhWHFFak5GRnk2a2xMcHM4NmIranNFc3RDdlczcjRHSHNCZFlXK3BydEhudnkxWmFSaHo5Tk4yNWVGN3JqYjlrbTBCL0ljZVN2UWY1eE1xSlRyUzFZWHVqdHlDblVuSnAyS3p4aTI5R250SmZka0JSaXVJTUNhN1VYMFhES01od0ptQWlyekp4TmowZUNvYUJWdFZhU0lkVDk0K3JYVDFJKzd4VWVWSHZKZ3RuTHg4eW1Odm5peTUvNG12MGt1V05mMHNZeHc5bWUrd1BGK1p0aXZ6Rm1qRkl0bGsxbm4wTnB1cENYS1hlQVBNTGd3UzhodVNBeDh4dyt1ZVRCTmxFcThLUnVUMkZKaCtwVmZMd1JiSS8xWWJqQjhya2JYSGtaQnlSbFFKUFJEV1JTRnNHc0xjZ2JVZ3lmaGxMeGVFVzVOb3F3MlZEaTdYbW5UZVFVZkdKSWxHdkJxMWQzbnBiVkJMd2NzdnVGOFZjMmJOcEVrMnZZaGVITURkT2UvNUFtVHoxNzBPeFliTmJBUlhQcDBSQWx0Ty93d2ZTcW1QN1RnN0d0VjUwOGhpWVAzdlRIeGdiNlVtUDJ5ZCszNTZMeUR5Q25Za0F4RTAxRWh0VnJDNUphSWJmbVMxd1JyQkNXSUxrSjNvSGtjTFRtU25sN1hDbjJQaUY2ZHBRaXV1Sk5INzZJc0RHWFVMTkFVKy9tM3BrMDZGa1p1SG11WDN4cXE1TFFTTnljVmFkWFhmVUJsZDcvdW0ybFpMdDg4V2xYakJvNzVCK3JQMlgvcGoxbHVtREsxUEd6S2F2NWF0Tkc5c08remVxaTY2dTc1NXQrUlh2UUN5elJXZWRlZ3I2cUg3ZzJsMW9QV0dHUmlmbUxSYUdBVWxFM0FIK3NCOUxZRktXYTJYV1U5L3FGTER5aERVWGJXOUpGNVNBYUtxQk1lQzhTVUJLMGtZcmNWTlYwdTRDUm9yL1U3UE1vbzVsUXdnekIvZTU2ODhtcTBUMkxpK3BqdzBmczJuVmoyODRiNWNXdnJIam9XYWZqWFVVZE0yTEZLMjF6TUFKTGQ2SU14ck9UbFVxUVFSbmtkTXZGUEMzUFdtd3dmS0Z6TlRKdjZRcWFPUnpvSEU3UnhuaHBXayttdWZGalpoYkQyVXRVTUQyaWJWT0M3aEpNVHh5b1dzUUl1b1ZqS09IMXQ3Z2ZIS05ocTlIYTUzSzR4alZuZUQzeCtQMEFwdUtOMy9QQ2pPc2JRb3g5YzJEQ0kyTjZEMXN6WnRGWnZhNDg1Yms5TkRubjdDR3pTa3RuRFY0MHoxUy84U2RmZS9pZkxVZnJxMStKSmE1Y05uekFJRnIxNVUxL2JFalJsb2Jlb0lLVVZBRDIrQjVzcmd6N2lERTRZR0VIbEMzbjlCUnhuSWZrQnpxNXdSalh3WWh3Z3gwVitWeHBwS0NSOEI0RnZ3Z1c2UlYrTWVmZ3RRY0hoSmljbHlzdXpqaGdjZHJ3UmpoUUlaQWFJRElzelhMeG9wZjBtMTNZd2t0Q1hCQ1NGYTdSL0ZKQnQyZHVtZjducDlhMHZYblp0S2tEWnBSZjF2Ym1qZWdiYnoxNTRyNEQ3R1FPc2g2KzdNd2VOVzBQOFJUb1VtNXoxZkMyRkdpMkErS2Exclh6RURVVzRTRVA4aWh0RHpaWFlhbGQ1cEh3K1AySDhLNENRZDVqdWhETnRLd1k1OTJyNlk0MWJVL2ZTSlB5OWZxUjMzTGQyMHpmZVA5OWpFTjcyTW4wTXhpUEJvajFSTWdnY1F4MlJLd3BEbWdBMjZFQllmNkNJd3B6WE9OUFkvc05Wdnc3Y2tiVVJnQUVzdEFkbmxscHZEc2RZV3JjRSs1WjBtL3dKcnJqeFcyMVE3YlRKSnU1UjFVblRwWnZpZW1INzNqeU5adjYyYnZ2Q3J5Q092RW9qQ2RLemhZZVNmZGtzRHMvNS9hRlVDZXNPRUNWRjJ6YjFhSWtwUmUzUjhkZ01ROHpZV0JTTVErVXhaeEp4V0Rib3R5a0ZtRXJneDluTXdYRG9pYkRNczBjU3NzaUY1RE5Sam9VOU9EN1RwV0NWenhOdFhPWnEvKzlsNXFTUHJMaWM4Z0J5dGdoTmoreWRhdUR1bjVvbHpPblExck01VHl5aTVUYkIvMWYya3BOc1RxUEwxWVkxQTUwTERBS2RPLzhxU0l2bkFDK0pFTmVKN2xlUFBlb2hGeXdBUmMwQk9QSm5zbUlzQ2swczFxUWFuR0h6bEc5TWFXblc0eDZHRTk5R2dkUm40THhwT3Z4TU4wTFRLcmVaMVNCMFZUQUJSVlY3Y1dqS2w0OHFrckFCUldpTVpYUGFuQWY2c09jUE9RcitGZWpDWDZ0VDJPZFhNcnlpWEZJRWlxd2l3U2htNnNZQTBodEEvcXdkSllqbFV4V3AzQ3hYZVR0Y2Q1S0Y0NGN4eUtEeDdmUU1yckYxWGo3R1F0djZaYSs4K3c3LzNaajJ4dVhqUnQ3d293U2FYbmJWOHZIamVzM3JRd0ZDVFp4ODVoSk0yZE5tZnZoUit3RWJyVlBYenludVNGUTRtR3ErTHBpWG4xdDJ5UENiZ282K2gzMzJldC8wVzkxNG02NzV6cU8yL3FmSFZha3RNQThyM2FNNHlLNGNBVFlWVkNUeUM4NXJpM3UzbmZNdmZzUjhGeVhUcDNXZDJiNThyYjMxZ0FiTms2Yytlb0hySmJUM0xKNFJxcXU3ZkVDemFKWHJUL1EvTE82TmYyLzYrZm1NSTRBZXNPT3lLb3VLMWNHVXUrWEI2bVhmZlhGVit3cm1seTRiTW1aWjUxLy9rTEpIL3FhTnJHWHZ3a2RZcS9RektGYnR1Vi9HMXI3MEVOclJmOEptNk9zaDNGRlNCVlpRd1NFTGlsQTZNcE9NdUJ0SndwV3BubURSMWpNOEJhbGN4cGZsS2FCaHViQ1BCMEpCMEVnbXVnOExQUjRhT0VDNjJNYXBDT29xKzRRTnJ1R3N6eXFBc1ltbGJ6Y2hOMjRuWVhRanJFTEdYb1huRjFMNy9QMCt2VzBGM2J2Zm03U1Zha2IwVXNuU21ZTlBXdmVnak5QbkZVQ1FybHMyQ2oyOTZNa2RKQjlNSEFBZXc0TFVNb0piVS8wN0hYdlRhSGZQOWs3elhFYzhHQVQ4Q0FFR2RjVjVreGtjUmNNVVNFd0JMWVpPNEgrSkJkVkdPZ1BjNzNUUzlPL29IcG85bDZrWHdrQXNRNk5JNG9BQ3RXQjdhL0ZuSHBieGMvUmhDRDQrUEIxNnJVTmtUWjBZYjFXVFREQjY4eXlzaGtkNEhYTXI2Nzg2UkNTUzN2M0h3RGc5ZVo3VWozcHZsNk4yMi9qL25VWTRMNWhuT2FGSW5zeUFtS0dRalg3L1dKS1hTZjdnOEFWNURNVE9XK3dmVm9pNkMyVURubWx4UnMwWlV3TVowQUFXVENyME0vTktpSlhkNWpUTURDbmJpdkdRaEpmdm1MUUtlRTFiYytnTGQxeHlteXJoWDBucDN1MmZXMGFFYTlQeSs4cG8wZ3R6bXNoVnMzSEZkSUk0NHp6YWZSNEdZeEp4dUc3eEdrWGp3WXVHMlpIM1VYSjF0NVJ0SGFZUld1QWZkdEtTbU0xQ1BlUzJvTnlwOEkxbHUrc3ZHclVVYmsyc04yL28yemR1M05TMWI0R0QvUTFSVHVWclh2UzAvYStkdG0rUWZJejViRVp6YU9uVDE3MXdzN0RyMXo2eG5qNTVacm85SVpwczJaZnR2MXA2ZWkxenc1dFl0OWNVMVJ4VW1QRDRPS2FqZGV1Kzl1SW9kL2NHQTROYXU0MXJLUWFNVitQb3dla1Z5MUR3RjdubUpycU1vMVV0d2l3YmswWEZ1Y29TSGdSMmlwZm5HTVJpM1BDN1l0endud3VMMHpFQkI0eFhBR0Jja0ZxWGdIU05kNFJMNnE5SXZYbzFxVDEyRXdUVHo3WjJDTldYMXJjTUtmUGhqOENQS2QyOXYwcmJlOE83RzFSMy9KNjc4MUxOVmlIQkovM290SVhQTjVGWm5aa2hiRnlaZE1VYzlCdVh0SGpFN09pYUsyQWprV1Y5a28xQUNpSEdSVWR2b0oxY2Jmb1VJUy84R2xtOXFUNWhkSlplZnNZUVhtSmdyVTUvcTRaSVJBU3Y1QW14NDQ1YVg3RkZWZlE1R3AyYWFEeDkzTzM3QVR0YTF1eS9JejY3dEx3R0lkNzE0MmE4UDYvNkhhQnJ5QWRWNVlBVFozcTFmUi9yVmNISXBtT0ttODVQYkRnNW9BYXVuRWhQVENiUGNWYTJRNTRzaDVncFFzVzBJK0R1dTQ5Y3JWOE1YOG1ZRjcxSkhobUJGZE5oOFY4cnJCTnEvdTR0V3A0TVBpYmJSWlFYN0dpenl4MVpncmxUam5CLzFmVFQ1Y3VVRjk4NW1WMS9qTDY2ZUtQRHIyZ3Z2RE54emlNTU5Pa0I5dEcwSU1SWGZjY3VVcTZxdTBTK1pKQzdMNmV5M1R3OFd2TDlIaTFaWkVFWWdYNVFidkRHU2xxbndnM3k4aVI1czVsNUdwckpGUXhuM1pqbjh5bi81cDM5MTN6YWV0YzlpbXRXc0MwZWZkdG1iTFZSMCtua3pYOWZ0ZnJyN3Z1MXpYMlo3Ykp0L1UrMTN2dk8rL2ovSXBDVHJnWnhsaUcvT0xpY1poY3NydGdRTHFXRWhFM2xoSk5ObnoxS1BkV0JoWmFDL3dxcDZhd3VMcVlXZDFBU3FPUy82SmJ2T3FXbFpKL1Bqcy9kZnNkVllON1ZIcGl6aEZqUW5GMlAzSXZ5TWFOb2ZRb0N2SHd4eGZjNFhMK1ExS0dOMDNnNG9UeFRRWGYrejZNejQzek9PMDFZOTVXNFJScm1IajBsYzB3akUzZGZPMEJyeHBqeVRpbldOQVVGSnN3RUxONm5IUHliTUpKUkRvRGtBSGJSdXkvVURqV1JPRllteXJKN0h2blhubHUyNCswcDJ2UFg2Uy8zRXlYL0tsdDlGcTJsdXNmdTFXNlNCMUFZb0NNemJXV3ZNOVQ5RHdyYVNOb1F3dm1UUkRCRXU1RGlFRUpQMEJtOG5ETi9RZXV1RFVuTXpIejUxT1pNNmJjODNTZnlTZE5oMzhuVGU3ejlEMVRKUFdGeGZOZjJ2Smw4KzNsbTVlL3RQQ3UrdFJkQzE1YXZybjhqdWFEVzE2YWQ0N2c0VlhzYTVyaDllYktqdlYwV01DV2VUK3krVkVvUUdzWjdhcXQ3R3YxeVI5UDVMWG5XNldaQlhvaXFYeTRneDZaNTRzbE5zUTVuSjRTV1JNbGdIREVETkFSN0c3bml4LzQ4SnNGT1prUXI2bXBWdWNwdzJiT25qMXoyQ2w5ZHQ0N2VjYU15ZmZ1ZkpHVGNXZDkvVjFuSWhtM04zKzU1YVg1aTE4NFo5NUxXdzQySXkybjA1WEtrM0tXRkpQUkJDSXRDbjZiWFE3YmVEV0o2bEVldXJEQnRGak1OcnJWVnFNRVpWNnNpVTRnR0owUGwyMFZZeTh3OW9tNTNObU82Q1NpVUlSSEpUTk9uZjdBZFJNM25Od3dxaTR6OGtYam1rbHJ4NlJIOVdqOEZkM3hoMTFEVHF5dlhqaWk1SzczQnc3clViTmdPT2YxdUtOT1JiWjBJMGx5SnRIanFYeEVySTMzRlZiSkYycDU1dngvWE5TWUsxVGN3eUZYRWVmSmpzTXM1MVhFTVVPSjhPSXFIcFpYWnRHRjU1elJVaXhDV3dBbE9ZUUpOdnJGQXZmamxDekRmSkY3TjJuYzg0ODl2K2d2L2J1VUxPMnkvT0lkenovK25PU092VVFUTmZ2ZmQ1dzhvRXZaY3UxYmtYOGRTTktFMEtNcytMTlpsbWVCdG9kSUxvYTJXQzR5T3p6T2w1am1hTTRGNXp4cW9la25UMmhNZGRmcGZoRjBnMm5PQktrRjE5cmtKSCtoSlFEZVkyQ2trazlQWU9jelFNWmNPTkVPa3YwK0xzZFNhNnRlYXZaR094MThSWVVEMEFteXk1L2dQVVdHRS9HajZoRmdPWm8xbXo3TVVsbzFsM1A3UmdDUmVDZ08wcmFLUllpWkUyaFRkWGFqN2FzWGQzMnVTT3pqbWNPR2ozUFEwOW05U3Y4K2RJOXJ6NUVTUnlxbFNLUDJ0Yng1Y00vRDZwd1p5eGUxN0pzMFptMFdqSCt2NjU1cGN3V2ZzRThkKzZ3UzVQZkgxQWwvTmkrWEx5MHJ3blY1V3NZb1ZiQjRtSytJOHhNUUZpcjRaQjVIME5FV1BYWnNHZEdMVEVsM3JTUlc4RFpLczVpSVdEb1VQV1pHTDM3Y0diMU1LTTcvbTh2NXhIOWFLeFVOZlVwOWFxaFVkQy83N0JCN25BNWxqNjlwZTNBamU0Qk9oSmVUZlliVGZXNXM3WEZER0d3N2NzK2xkMDlFQkRyeDdrdnZrUlNlTTYxVlBwRnY3dGpIaEhQQUJGK0syVGhBMm1mVURCbWNpU3k2QUt5MjFrNXJWREx3V2t1ZHJkU3BmUEl5L09PelRDT1BIckMwV0o0aFRvajJUV1NydVZMRHErQ1VsMWdLRU9MSCtVeWRYWGJEaC9pbExvTzhxa3VCWXBZbStRK2w0b2NrVDVlVEZRZ0ZlM01IS1RZRXdYMERpc1J1Qy9pdHhtZjA1R3VpOHIzRWlmSzAzc3RuK0RzV2VEVEQrSHZXYVA2SDdONlFuQ3pOY05ZbjYwQVVxc3RmVHN5dEEwUS9EMzhQOEpYSEdsOTRiRzdJNEtVSnJxSUZTODZBSllOVEdrbnZvRFcwbW01bWM5aDc3QjAyZHlXZDh1MGhPb1hkZitnNzl1ZDlUOXg1dTZGSXQweWZQdnYwMDJkUG4zYUxwT2kzMy9tRTlDN3RSM1UyamozTG5tRmphSTcyWnord0RmUk1hcU5XdnFiOGlRc2ZhTm04WHAwOTZmb2xLNVplTjNHT3V1SDJ0OFVhQjRVc2srZkt2NFZqSy9IZ09xQW1tcEVEaVlENFVHalJZd2NQUHJaVWZDeWp2dzdRWDdQejJKTDJBOUoxUFRucHZHQWMvaWxkWkpnbS9jajIveXpGaGd3WFZvUDRwWUZMc1FFVVhhOUo1NlA5K0c5Ui9sdGhINDJ1MGt1TGI1bTBudmJoT2w2OU9aMVBpbk5sNllKc2sxM2tpUHRxOUVqallqK1VJODk1OUFyY1VNUEw5eUhSTTFvZUJFb3E4S2QrWmlIM1AwaVZhbkcrUTBRaG9vY1NJaWJXMFdKYTZFMzZaZG5TS2xxODRhOTkrL1J0c2pvMjJwVWhvKzY0ZHZqY01hZGQrZCtFMnpaVG5uRHRaWDJubE1jV2oreFRPemprQzQzdjNudjRVTGFWN3VyZjUxZDlRVDRUbE1lbGNYd090cGEwOSsvOGZBN1drTEhxTDRuaVNnbU5SK2lFNTJpVHhENVRIcWNPbFIzaHNwN0ltdWc3UkNWZTNGVklURjl4R0dRS3hwZlMzV0pMQWcxWGZMbmJzMkRzbThFaW5WblJnSlRlS3JMRGFzNmNpZVh2T3dkTmZ2ZythNmIvb0d0dnlqb25qRnQweFYyMTNXdmpxS09EeUFiNWZ2a2hlS3JRMFlDY29TR2FpSXZQUVY5KytSZ3RZdjkralA3T1BOcEFiNlpyMmNvQVc5bCtBR08zaVBWRlZqZU0zVWVDcElpVWttdVB1OHFJcHpKaXFWR29zTlFJZ0xKZW5NRmx2bm8welp1V3RSYU85U0RJRi9QbHltS1ZmRkJEaCt6eWVOMGhzVmpaaUJSaFg3OC9yOWljampEM0ZKMFdLNFY0M3ZienhVcDhqWnNjbHhOb1VEOWJ0Y1I4b0N4T3ZuWnBQblYyWGI2a0RuNzU1U1BsRXFWN3dZdGFSSStvMVFWVWVnaG9Nc1QyODBuT1JjeFZlMGk4NkZzdWs3Rk9yOGN6aUxQMFNoSENhUXQyNW0vekJpamdMejhRR3hERVF2cThMV2t2aHBOeEcxKytsTFNKRUIySHRIT2J5eEl1NDNTV1FhQUdlOEpWa0YxYlRKRzJMcTJOZU9ibnJhWlc2dnlpME50SWQxSG56enRPbFcxQTdJMWQyaHZsR1VDM0l2ckRRTllFcEl4VWJ6WWpkYUY3bS9jaW1MM0FPZFVYd0Z5b2pOTWY2NkMvcEFVTGo3a1NYbFF2b1FCaHltd2xRSFdGRFZ2aGNNc2VqMmo1eTRVOGhkSjBvUm5hQjFjblE1N2o4S2dDcEYrRXJiY0d4UWE0WUdsV3NPZy9kNU1WSXVUeHU4b2U0M0h6RjNyTDZLbG1TRFg3NWxTMUNtemhCclBXVmR5cG54MklGbE9iM1RsK3EwWDhWdHZlMGxuck15cFIrZ0RMN09ZS2F0d0NxcHl2b041V2FxOEVZcjFtLzJONUxXaUNHZzVWOFRYVWxiaDBtbGZsZVNvVXlocFZ4ZGhQWmZlRzI5dkFHNU5pajUxUWtJaXFrWmxsTmpVU2lKam1EZ3ZZV0NmdGVmRHpzMmpkUDk4ZHZiNnFkM0pSLzFHVDJkdm5qQjAyYXZiV21IekZSOTl0MTg5K2VtZ2Z0bi9ORTQvSG9udkM4VkdEaDFQM1RkT2ZQM240bEdzdU9Qd3cyQVR2S2JHOEFFUW5TUTNrUGMza0xaS3JSQmIwQUY3d2FkT28yZGlTODlOQ0d4UEhzWGEzSFNOVXBkL05OM3hDSlduTUdOMUFaWnBBWmZxazlLb1dvd2JrWGVQVGE1RS9vbE1IR1ltcmhJR0JIc2dFSUhjRmpKR2l1SThNcjV3MjRxWE5vQjlaNEZ4TkxTWXRtQW8wYXZEVUhqaTMxSnlDYzZYbHlNQW8xdW1UY0tqMzBMRER2c0dmQzlNTVhtUlhDbnNKOEpKVlJ2c2YybElDM0JUYlcxUFFEbis1UGVVTTZoemIzcURTaHpwL3FVbEZtUS9XMlU5MHFzalhvZ1phUk8zZjhsSW5mN1QwWi82SS9ySS9BaStrKzVGalhodWZXK3pxbVBRNFgvRC9YeDFTNTdtRERpNFU1ZytRRVYzbUVNNm56bW1GV1FSNkl2aWlyak1KOHI2WFgyNEx0RThuU084SWUxTkUzWS9USy96UWZhWWZzaFZXa2ZvTG1pWlJNVUdKWnVnK3ZqT3lvRE95Rkp5UmJ2SHBGVWhzR1RDZ0xDVzhrcWlBNE9vRUo1K0FjSUxDNms3Zk1WeEJGMlNEdUdRa3VRY0tvV0w1QTBKOWJEaXZienFrLzFRdExEaWs0MWNOKzNLSDlKOXFoL1Mzd2h0Uk1oMXloLzZXTDRpTnhBczdGRW9tTUVGa2dlVW9pYlJQQlZSbExLRkV0WFU2amQ1UHk5L2RlSnZ5U2ZUZ041RjE2MFQveWZuS0JLbkkwc0p4NWlpK1M1SkFtSHFJTDkzbTRLVG9HTlRZQ1FtaTh2aGR3QXU3akx3SWVUSEZKQjJGZzFDbjNkR0VTWjEvOGZTcEYxMDBkY2JLcFgyNjF6WTIxbmJ2WS9GUFhMSms0aWxubjMxS1hYTnpYWTgrV2FUeGJPVlZlWnJsUnhJZ1l3bHZIRkZhdC9sVXQ0MzN6dkZOV21CSUFUR2tBRjlha25lSUllRUVxRDJBQUlxM1gvbmNmSU1XUTFaNTkwaGhZQjNsREZUYnN5K2MzbWQ3WXlLejhjSVp3NHpHWklQeXcrd2JwZ3hLMWMrNVpzcmcrditDMWY5ZmY1UElQZEkwNVNONUFOZjNDRUYvSjhtdCtFS0FXZWp1YTRDL3VrY1pJRTM3NUJQOG0zdWxhWllQLzl2Zm9JcmRTeTI5K1I5Uk1rZitpdjVKN1NQMndTSmQ5c0VDRkdzMzk4SGk5VDNGbkNBMEsyMXp6aHl6dHFaVy91cXBlUjhNSHpOcy9heW5PRTM5NURmcEtqNnYzcjZ2bHUyLzdxdVZRWkRmcjdyN3QyZU5VdUluclorOWZmdnBIdzRUUEJyQ25xRFgwL0wvZVo4dVhqM0w0TjRRUTZxN2YwZlBHdlh0c1BVelAveGducmlmVE9iTFgwbHRuRjRINE5XUjNFYWNHZk8ydkhRUUtsQU9BWm5mSEJDcFdNVXJtS0E3TmQzQ043ckRIVXdVVWxDZGRzWWs0dTJIOHptUHZxUGpqK1ZWSjZiSlpJVDhwdlF4OEl6d01aMUlrTGhnQm90ZnRvNHhtZnRpV0FVKzBzd3htYnRpNkI1TmQyWjFYbWJtUW1ybmFuUDcwUWpPMzJmNHUvem0yQUtieDNibU44NVZQaUc5ei9rZHdKSFlVM3FnblRzZXNRT1M0RHYyekdLUHZ3OUdFdW9RZ2JsdmxZK3ZqZXdzanVaakJmUFVNZklaMDBsUUNya09BdHRrU3N6OVNGeGtuRm5KdHRnekdYTmJFa04xUUdKeDdENGxGRHRSeFdacDdmdXY0anBJc2NRTXQ2WHh0TzlUVW90YnZlTEJkWHl2a25kcDRxOWl3NUxDUytoZ0Q3S0JIdVc1VXB4UTNjcmRpOFZGaWtYbk1pNFZ0WFN5RDU3azl4RFp2VlRXT2FjZlRqWkkwYTczMGFXMGVhdU8rMUM4ajVtUURmOVorb1Y3bWh3OUlPKzFQRXZxY0kreWFpTG1qM01PU1V6NTV3bXRkcmpySUxQS0ErOXdHOHdTM0JDV240dUFWb2x6b1RUdlZ1dmVZa2hxT20yVUFWK0NSY0RPc3U2OE83S0VyeEhPZFMvRGI5M0YyaVBzWWlOR1JUVU1NY2tuOFJKWkNJcnd6Y05ubmQxbTMxMENlKzlJNTk2N3VGa3A2elROUndiUXZ1dldyYVAxaC9vdkhScHFXdEpuK1RYMEwrd1VmRjJ6b3UrNW1WQjI1UW5mc2pmcC90Ly83dmZ1TDJpWno3dmZGN2pqZWk5OHRWMTFrNlo5N1BWUnp5YzJvYk9qNU44cTJOTnZCYSszam9qTmk2Z2I5TVRHTFNobjhXSXF3czhYVHZGam9wamQ1enlNcVNCVXNjbXRoVmRoTFRKMjEzTmtvQktPREZCRStZaUlLeEVmN2txVDkzYUVPbXl1TkJ3OHJoQWI3a3ZsOG9mYVo4Y1NXa0tqR3A4aGc2TnFPU0ZidFZIMHBpdlcwaW1iTDFSWDNMWktQWFBFT2FwbCtKbG5Zam1DalpMSzJPL29XVzBmMHlobCsybDN5bllSdm9HRzBrZnBBemJSMUxFL2o3WFRYb0ZXcFdPYXdvWGZ6QTl6bXFJQk5CMXZjUGg1K0lQL0F4czRrb1VBQUhqYVkyQmtZR0FBWXZIR3ZLZngvRFpmR2VRNUdFRGd5QWRCUFJCOVZiNWg2Zi83LzB6WnA3SHpBdFZ4TURDQlJBRThBQXVOQUFBQWVOcGpZR1JnNEVqNlc4VEF3TDdsLy8zL3A5aW5NUUJGVU1BTUFLakRCM040MmpXUlRXZ1RVUlNGdjNudnZneFk3VXBjU0czVEtzUmcwaENwZEpxZ1RVMnBOcmhRQzlxYUlnWmJVQ21DNHI4dTFCWWh4RCtLTGx6b1RxMmc0a0pGSVNBaTRnKzRycnJSTHBWdWhLS2cwbmlua29FNzU4Njg5ODQ3NTF3elN5LzZlRC8xTlFQMTN2dkRjZHZJUlhsSVRBWlk1NjZUbG5tUzNrc3E1Z0ZENWkzcjdTUnRVaUR0L1NKalU0eWJSWnd6czdWdk1rbkpsdGtnWXlSbGtBNDV6eXJwMWU4VEZKVXJMVVh5Y3BSdTd3MUY3elZOZG82TTdPVzArT3l6bjJqeEcxbnJYaEIzQlFLWG9zT05LellSeUEvRmZsS1JIZ0x6ams2WllxVjdwdjhmRWZobGdraTdyaytSZDBuRmU0cG5kZTB6SlZkaWRhU0JtS3ZTNWo5bWhidEZxenVtbXQ1VE1CdXAyQjBzTmwwMDJDT3F0NDloYzVDY2pGS1FtN1h2MHFsYXExcDU4dVkzV2ZXMnpTMGphcTRSTXp0clgyUkcrMHRFL1VQS3U1U29uS0ZiUGhKWHozbTdtejVwcG1UK2t0RHNXdVVLTFpFRG11ZGQzVmRsdWJRemFDNFQ4KzR6WVNvMHVEV01oTmxyVmx2bEJsM3VwUEw0WEkwMFU3RFBrVERqc0RjVGJQZm1HTEFmeUMza3BKNFgvTVdWTzlRZGF0RDc3Q2wyS2Y5aCs1UXhld0g4SlZCSE02UXpmcVdWK0YvaDdMMzlpaU82UisrcGx4dm1kaVRMSGU4cmU3eHBzdDY4K3VwaDFHNm0zMlkxNHllVXczTW1ROExrMkJSeVNrem5PYzBXQ2VBZnRVZDBaQUFBZU5wallHRFFRWUl4REJNWXE1ZzRtTll4QnpHWE1TOWh2c1Q4ajhXSkpZdWxqV1VWeXlGV0lWWS8xaE5zTG15ejJCbllxOWlQY0lSd25PTDR4Mm5HNmNPWng5bkUrWXByRzljLzdnYnVSenhtUEN0NHVYaXJlSC94bWZCTjRHZmdEK04vSU9BbnNFQ1FRekJIOEk5UW1kQVpZVFBoQXVFSEluWWkwMFRlaUVxSkdvbW1pTGFKTGhBOUlCWWtka0JjU255SytEZUpNSWtya242UzB5VHZTVGxKMVVnYlNLK1MvaVpUSWZOSjFrZjJuSnlLWEovY0NYaysrUVQ1RFFvY0NrRUtCUW83RkRtQXNFeUpTMm1DMGo3bEF1Vkp5Z3RVa2xRNlZBNnBQRk5OVUwyamxxSDJTbjJGaG9MR09vMHJtZ3FhR1RoZ21XYUw1aVROTlpySE5OOXBpV25aYVhWb25kTVcwbzRBd2hydEJkb0xkRFIwNXVnY0FRRERxbGY2QUFFQUFBQ1lBRVFBQlFBQUFBQUFBZ0FCQUFJQUZnQUFBUUFCWmdBQUFBQjQyb1dUelc3VFVCQ0ZqKzBBclNnVklPaUNCYkpZSUpDSTY0U1VRcENRS0dvclVJVlFXOUZOTjI0YVNFU2FGTWRRZUFXZUJMRml6UU1nZmlUMjhCQThBNS9IMXlWR0t0WFZ2VDR6YytmTXp4MUx1dWo1Q3VUVnBpWGRZeGZZMHh4U2dYMU5hZFBoUUpHMkhhNHAxRHVIVCtpOVBqaDhVcGU5ZFlkUDZaRzM3L0MwWnJ4UERwL1dCZSt6d3pPNjZ2MXkrSXkyL1RLSFdXMzZaYXl6bXZNL09ueE9VMzdwZTE2aC84UGhMOXo1N2ZCWHhVSE40VythRFZvT2Z3Y3ZGZmhub0V2QkU2MW9wS0V5YXRsVndqY0JkZER0NjYxUzlmVmNQYk91b2hzaERkUkZhaXBXUXplMWptNkhuYkZEYldCTmtKZVFCdkJOK3JTUHVOcytoaXM4WkhzS1M2b3hPZVVaaC9oRTdKalYxQzM4NzA3a2xlT005UXlPVjhiWXc2K284NXBlY3l2U0hiVTRjNDg2UGgwdElsL0hYaWRhcU51R1VvdlROTHpxK0dPVDNwaWxaZmlBOHdvcngxdVZPc29xNm9kVkhGVmxuOHBDNjM1RzFJU2JYZTN4VGZVQzNZaEtxbThRVmFTcUpYKy9QVDJnNXRSNE04N0VlbFpFei91WW9jLzd1SWF0ZzJhSTNDVnFTTCtHRmoyMVhIcjIzdmVaaDRSN2hWVDF1WUhtMzdkcGtrVnNrNVBoMmRZODY4QldCTTlmcm9qN0tYblBrL2trNXhqTm1oNVN3N0llMDZWbE9saHcvbi9panB1aExiaDM2R1U1OHczalhMRStGVldtbG4yRE05YUNUV2QrTGpBZDVYUXRXbmVLeVJwWWZVWHRYZVR4eFArMG9aZG8rdGhTYklNL2ptMmpvM2phYmN4SlQxTnhHTVhoMzFzNjBFSmJDaDBaUkhBQW5MajNsZ3NVQnlpbEZ4eEFaZ1hIRXJRbElVcEtha0xDaGdRWElMSTBmZ1ZsaHdJNzNjQjMwSVY4RXhKTjczL3AyVHg1ejBsZUhKUno5bzVGL3BmUElBNnBFS2U0cU1DSkN6Y2VLdkhpbzRwcS9BUUlVa09JV3VvSUV5RktqRGdKNm1tZ2tTYk8wY3g1V21qbEFoZTV4R1hhYUtlREsxemxHdGU1UVNjYU9nWkp1akRwcG9kZVV2UnhrMXZjNWc3OURKQm1rQXhEWkxFWVpvUzczT00rRHhobGpJZU1NOEVrVTB3end5eVBlTXdjOHp6aEtjOTR6Z3Rla2hNM203em5FN3Q4NEl0NDJPWWpwM3hsZ1R3RnRsaG1uUTErOFlmZlVpbGU4VW1WVkl0ZkFoS1VHZ2xKcmRSSldDTHM4NDFEampqbU93ZWNzQ2RSZnZCVFloSm5SeEx1L1BMYVNrSDNsTjRzYVpvMnBEU1VLZHUwcGl6dnhyOUJxU3NOWlZMWnBUU1YzY29lWmE4eXBVemI2dXF2cnZ0ZUwrVkx4VmVMdWRXQ1hSbVdyV2s1czZYaTIvSmhXcG0vSkVGZlVRQUFBSGphMjhINHYzVURZeStEOXdhT2dJaU5qSXg5a1J2ZDJMUWpGRGNJUkhwdkVBa0NNaG9pWlRld2FjZEVNR3hnVm5EZHdLenRzb0ZGd1hVVHN6aVROb2pEdUlFVktzb0ZGR1hkektTOWtkbXRETWpsQkhLNUl1QmNEaUNYVXcvT1pRZHlPWVRnWERZZ2wvME5uTXV0NExxTGdiWCtQd05jaEFlb2dKc0R4bzNjSUtJTkFITStOb01BQVZqNTBDVUFBQT09IikgZm9ybWF0KCJ3b2ZmIik7CiAgICB9Cl1dPgoJCTwvc3R5bGU+Cgk8L2RlZnM+Cgk8Zz4KCQk8cmVjdCBmaWxsPSIjZmZmZjAwIiBzdHJva2U9IiMwMDAwMDAiIHN0cm9rZS13aWR0aD0iNSIgeD0iMTAiIHk9IjY0IiB3aWR0aD0iMjM2IiBoZWlnaHQ9IjEyOCIvPgoJCTxsaW5lIHN0cm9rZT0iIzAwMDAwMCIgc3Ryb2tlLXdpZHRoPSI1IiB4MT0iMTAiIHkxPSIxOTQiIHgyPSIxMCIgeTI9IjIyNSIvPgoJPC9nPgoJPHRleHQgc3R5bGU9ImZvbnQtZmFtaWx5OiAnUm9ib3RvIFNsYWInOyBmb250LXdlaWdodDogYm9sZDsgZm9udC1zaXplOiA1NnB4OyB0ZXh0LWFuY2hvcjogbWlkZGxlOyIgeD0iMTI4IiB5PSIxNTAiLz4KPC9zdmc+";
    var docHead = document.getElementsByTagName('head')[0];
    var newLink = document.createElement('link');
    newLink.rel = 'shortcut icon';
    newLink.href = 'data:image/png;base64,' + favIcon;
    docHead.appendChild(newLink);

    //favicon
    function updateClock() {
        if (!myData)
        {
            document.getElementById('clock').innerHTML = `Lade Daten`;
        }else{
            const now = new Date();
            const berlinTime = new Date(now.toLocaleString('en-US', {timeZone: 'Europe/Berlin'}));

            const day = berlinTime.getDate().toString().padStart(2, '0');
            const hours = berlinTime.getHours().toString().padStart(2, '0');
            const minutes = berlinTime.getMinutes().toString().padStart(2, '0');
            const seconds = berlinTime.getSeconds().toString().padStart(2, '0');
            const months = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
            const month = months[berlinTime.getMonth()];
            const year = berlinTime.getFullYear().toString().substr(-2);

            const timezone = '';
            if (myData['noDTG'] == "1") {
                if (myData['getSeconds'] == "1") {
                    document.getElementById('clock').innerHTML = `<span class="bold">${hours}:${minutes}:${seconds}</span>  ${day}.${month}.${year}`;
                } else {
                    document.getElementById('clock').innerHTML =`<span class="bold">${hours}:${minutes}</span>  ${day}.${month}.${year}`;
                }
            } else {
                document.getElementById('clock').innerHTML = `${day} <span class="bold">${hours}${minutes}</span>${timezone} ${month} 20${year}`;
            }
        }
        
    }

    function toggleSpoiler() {
        const formContainer = document.getElementById('formContainer');
        formContainer.style.display = formContainer.style.display === 'none' ? 'block' : 'none';
    }

    function toggleSpoiler2() {
        const formContainer2 = document.getElementById('formContainer2');
        formContainer2.style.display = formContainer2.style.display === 'none' ? 'block' : 'none';
    }

    // (Fügen Sie hier den JavaScript-Code für Stärkemeldung hinzu)
    document.getElementById('einheitenListe').addEventListener('submit', function (e) {

        try {
            var einheiten = JSON.parse(myData['einheiten']);
        } catch (error) {
            var einheiten = [];
        }
        e.preventDefault();
        const Typ = document.getElementById('Typ').value;
        const fuehrer = parseInt(document.getElementById('fuehrer').value, 10);
        const unterfuehrer = parseInt(document.getElementById('unterfuehrer').value, 10);
        const helfer = parseInt(document.getElementById('helfer').value, 10);
        const Organisation = document.getElementById('Organisation').value;
        const Ort = document.getElementById('Ort').value;
        const Rufname = document.getElementById('Rufname').value;

        const einheit = {Organisation, Ort, Rufname, Typ, fuehrer, unterfuehrer, helfer};
        einheiten.push(einheit);
        //localStorage.setItem('einheiten', JSON.stringify(einheiten));
        myData['einheiten'] = JSON.stringify(einheiten);
        sendKeyValue('einheiten', JSON.stringify(einheiten));

        aktualisiereEinheitenListe();
        aktualisiereGesamtstaerke();
        //this.reset(); // Formular zurücksetzen
    });

    function aktualisiereEinheitenListe() {
        try {
            var einheiten = JSON.parse(myData['einheiten']);
        } catch (error) {
            var einheiten = [];
        }
        const einheitenListe = document.getElementById('einheitenListe');
        einheitenListe.innerHTML = ''; // Liste leeren
        einheiten.forEach((einheit, index) => {
            einheitenListe.innerHTML += `${einheit.Organisation} ${einheit.Ort} ${einheit.Rufname} (${einheit.Typ}): ${einheit.fuehrer}/${einheit.unterfuehrer}/${einheit.helfer}//<span class="unterstrichen">${einheit.fuehrer + einheit.unterfuehrer + einheit.helfer}</span> <button onclick="einheitLoeschen(${index})">Löschen</button><br/>`;
        });
    }

    function einheitLoeschen(index) {
        try {
            var einheiten = JSON.parse(myData['einheiten']);
        } catch (error) {
            var einheiten = [];
        }
        einheiten.splice(index, 1);
        //localStorage.setItem('einheiten', JSON.stringify(einheiten));
        myData['einheiten'] = JSON.stringify(einheiten);
        sendKeyValue('einheiten', JSON.stringify(einheiten));
        aktualisiereEinheitenListe();
        aktualisiereGesamtstaerke();
    }

    function aktualisiereGesamtstaerke() {
        try {
            var einheiten = JSON.parse(myData['einheiten']);
        } catch (error) {
            var einheiten = [];
        }
        const gesamt = einheiten.reduce((acc, einheit) => {
            acc.fuehrer += einheit.fuehrer;
            acc.unterfuehrer += einheit.unterfuehrer;
            acc.helfer += einheit.helfer;
            return acc;
        }, {fuehrer: 0, unterfuehrer: 0, helfer: 0});

        const gesamtstaerke = document.getElementById('gesamtstaerke');
        gesamtstaerke.innerHTML = `${gesamt.fuehrer} / ${gesamt.unterfuehrer} / ${gesamt.helfer} // <span class="unterstrichen">${gesamt.fuehrer + gesamt.unterfuehrer + gesamt.helfer}</span>`;
    }

    document.getElementById('einsatzBeginnForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const einsatzBeginn = document.getElementById('einsatzBeginn').value;
        sendKeyValue('einsatzBeginn', einsatzBeginn);
        berechneUndZeigeEinsatzzeit();
    });


    document.getElementById('EigeneInformationen').addEventListener('submit', function (e) {
        e.preventDefault();
        const eigenerFunkrufname = document.getElementById('eigenerFunkrufname').value;
        sendKeyValue('eigenerFunkrufname', eigenerFunkrufname);
        const BezeichnungFuest = document.getElementById('BezeichnungFüSt').value;
        sendKeyValue('BezeichnungFüSt', BezeichnungFuest);
        SetzeEigenerFunkrufname();
    });
    function SetzeEigenerFunkrufname() {
        if (myData.hasOwnProperty('eigenerFunkrufname')) {
            // document.getElementById('headDiv').innerHTML = "<img alt='' src='data:image/x-icon;base64," + favIcon + "' />" +localStorage.getItem('eigenerFunkrufname').toUpperCase();
            // document.getElementById('headDiv').innerHTML = "<img alt='' src='data:image/svg+xml;base64," + FüSt + "' />" +localStorage.getItem('eigenerFunkrufname').toUpperCase();
            document.getElementById('headDiv').innerHTML = myData['eigenerFunkrufname'].toUpperCase();
            document.getElementById('eigenerFunkrufname').textContent = myData['eigenerFunkrufname'].toUpperCase();
        }else{
            document.getElementById('headDiv').innerHTML = `Rufname: Bitte eigenen Rufnamen angeben`;
            document.getElementById('eigenerFunkrufname').textContent = `Rufname: Bitte eigenen Rufnamen angeben`;
        }
        if (myData.hasOwnProperty('BezeichnungFüSt')) {
            // document.getElementById('headDiv').innerHTML = "<img alt='' src='data:image/x-icon;base64," + favIcon + "' />" +localStorage.getItem('eigenerFunkrufname').toUpperCase();
            // document.getElementById('headDiv').innerHTML = "<img alt='' src='data:image/svg+xml;base64," + FüSt + "' />" +localStorage.getItem('eigenerFunkrufname').toUpperCase();
            document.getElementById('FüStDiv').innerHTML = myData['BezeichnungFüSt'];
            document.getElementById('BezeichnungFüSt').textContent = myData['BezeichnungFüSt'];
        }else{
            document.getElementById('FüStDiv').innerHTML = `FüSt: Bitte FüSt Bezeichnung angeben`;
            document.getElementById('BezeichnungFüSt').textContent = `FüSt: Bitte FüSt Bezeichnung angeben`;
        }
    }


    function berechneUndZeigeEinsatzzeit() {
        if (myData.hasOwnProperty('einsatzBeginn')) {

            const einsatzBeginn = new Date(myData['einsatzBeginn']);
            const jetzt = new Date();
            const differenz = jetzt - einsatzBeginn; // Differenz in Millisekunden

            const tage = Math.floor(differenz / (1000 * 60 * 60 * 24));
            const stunden = Math.floor((differenz % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minuten = Math.floor((differenz % (1000 * 60 * 60)) / (1000 * 60));

            document.getElementById('einsatzZeit').textContent = `${tage} Tage, ${stunden} Stunden, ${minuten} Minuten`;
        }else{
            document.getElementById('einsatzZeit').textContent = `Einsatzzeit: Bitte Einsatzbeginn angeben`;
        }
    }

    function aktualisiereEinheitenListe() {
        try {
            var einheiten = JSON.parse(myData['einheiten']);
        } catch (error) {
            var einheiten = [];
        }
        const einheitenListe = document.getElementById('einheitenListe');
        einheitenListe.innerHTML = ''; // Liste leeren
        einheiten.forEach((einheit, index) => {
            einheitenListe.innerHTML += `
            <div class="einheit" id="einheit-${index}">
                <input type="text" value="${einheit.Organisation}" onchange="aktualisiereEinheit(this, ${index}, 'Organisation')">
                <input type="text" value="${einheit.Ort}" onchange="aktualisiereEinheit(this, ${index}, 'Ort')">
                <input type="text" value="${einheit.Rufname}" onchange="aktualisiereEinheit(this, ${index}, 'Rufname')">
                <input type="text" value="${einheit.Typ}" onchange="aktualisiereEinheit(this, ${index}, 'Typ')">
                <input type="number" min="0" style="width:40px;" value="${einheit.fuehrer}" onchange="aktualisiereEinheit(this, ${index}, 'fuehrer')">
                <input type="number" min="0" style="width:40px;" value="${einheit.unterfuehrer}" onchange="aktualisiereEinheit(this, ${index}, 'unterfuehrer')">
                <input type="number" min="0" style="width:40px;" value="${einheit.helfer}" onchange="aktualisiereEinheit(this, ${index}, 'helfer')">
                <button onclick="loescheEinheit(${index})">Löschen</button>
            </div>
        `;

        });
        einheitenListe.innerHTML += `
                <div class="einheit">
                <form id="einheitForm">
                <input type="text" id="Organisation" placeholder="Orga" required>
                <input type="text" id="Ort" placeholder="Ort" required>
                <input type="text" id="Rufname" placeholder="Funkrufname" required>
                <input type="text" id="Typ" placeholder="Einheitentyp" required>
                <input type="number" min="0" value="0" style="width:40px;" id="fuehrer" placeholder="Fü" required>
                <input type="number" min="0" value="0" style="width:40px;" id="unterfuehrer" placeholder="uF" required>
                <input type="number" min="0" value="0" style="width:40px;" id="helfer" placeholder="He" required>
                <button type="submit">Einheit hinzufügen</button>
                </form></div>`;
    }

    function aktualisiereEinheit(element, index, eigenschaft) {
        const wert = element.type === 'number' ? parseInt(element.value, 10) : element.value;
        if(typeof myData['einheiten'] == "undefined")
        {
            //setTimeout(aktualisiereEinheit(element, index, eigenschaft), 2000);
        }else{
            einheiten = JSON.parse(myData['einheiten']);
            einheiten[index][eigenschaft] = wert;
            myData['einheiten'] = JSON.stringify(einheiten);
            sendKeyValue('einheiten', JSON.stringify(einheiten));
            //localStorage.setItem('einheiten', JSON.stringify(einheiten));
            aktualisiereGesamtstaerke();
        }
    }

    function loescheEinheit(index) {
        try {
            var einheiten = JSON.parse(myData['einheiten']);
        } catch (error) {
            var einheiten = [];
        }
        einheiten.splice(index, 1);
        myData['einheiten'] = JSON.stringify(einheiten);
        sendKeyValue('einheiten', JSON.stringify(einheiten));
       //localStorage.setItem('einheiten', JSON.stringify(einheiten));
        aktualisiereEinheitenListe();
        aktualisiereGesamtstaerke(); // Stellen Sie sicher, dass diese Funktion existiert und korrekt die Gesamtstärke aktualisiert
    }

    document.getElementById('darkmodeswitch').addEventListener('change', function () {

        if (this.checked) {
            aktiviereDarkMode();
            sendKeyValue('darkmode', "1");
        } else {
            deaktiviereDarkMode();
            sendKeyValue('darkmode', "0");
        }
        
        });
    document.getElementById('Uhrzeitformat').addEventListener('change', function () {
        if (this.checked) {
            sendKeyValue('noDTG', "1");
        } else {
            sendKeyValue('noDTG', "0");
        }
        updateClock();

    });
    document.getElementById('sekundenswitch').addEventListener('change', function () {
        if (this.checked) {
            sendKeyValue('getSeconds', "1");
        } else {
            sendKeyValue('getSeconds', "0");
        }
        updateClock();
    });

    

    function aktiviereDarkMode() {
        // Beispiel für Dark Mode Aktionen
        document.body.style.backgroundColor = "black";
        document.body.style.color = "white";
        document.body.classList.add("dark-mode");
        document.getElementById("darkmodeswitch").checked = true;
    }

    function deaktiviereDarkMode() {
        // Beispiel für Light Mode Aktionen
        document.body.style.backgroundColor = "white";
        document.body.style.color = "black";
        document.body.classList.remove("dark-mode");
        document.getElementById("darkmodeswitch").checked = false;
    }
/*

    function exportLocalStorage() {
        let storageObj = {};
        for (let i = 0; i < localStorage.length; i++) {
            const key = localStorage.key(i);
            storageObj[key] = localStorage.getItem(key);
        }
        return JSON.stringify(storageObj);
    }

    function importLocalStorage(jsonString) {
        const storageObj = JSON.parse(jsonString);
        for (let key in storageObj) {
            localStorage.setItem(key, storageObj[key]);
        }

    }

    document.getElementById('fileOutput').addEventListener('click', function (event) {
        const data = exportLocalStorage(); // Nutzt die zuvor definierte Exportfunktion
        const blob = new Blob([data], {type: "application/json"});
        const url = URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        var datum = new Date();
        a.download = "Einsatz" + datum.toLocaleString() + ".json";
        //TODO: der Dateiname muss noch schicker
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    });
    document.getElementById('fileInput').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const bestaetigung = confirm("Sicher? Alle bisherigen Daten werden überschreiben!");
            if (bestaetigung) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const content = e.target.result;
                    importLocalStorage(content); // Nutzt die zuvor definierte Importfunktion
                    location.reload()
                };
                reader.readAsText(file);
            }


        }

    });*/
    document.getElementById('reset').addEventListener('click', function (event) {
        const bestaetigung = confirm("Sicher das alle Daten gelöscht werden sollen?");
        if (bestaetigung) {
            localStorage.clear();
            sendKeyValue("reset", true)
            location.reload();
            loadJSON();
        }

    });

    function updateeverithing() {
        console.log("Update everithing function is called");
        loadJSON();
        setTimeout(aktualisiereEinheitenListe, 2000);
        setInterval(updateClock, 1000);
        setInterval(loadJSON, 5000);
    }

    updateeverithing();

    //TODO: Log anlegen bei dem jede änderung mit timestamp (DTG) im lokalstorage abgelegt wird
    //TODO: Die DTG function auslagern damit auch logging darauf zugreifen kann
    //TODO: auch beim logging auswahl welches zeitformat?
    //TODO: zusätzlich zum Funkrufnamen optional den Namen der Führungsstelle anzeigen (zugbefehlsstelle etc.)
    //TODO: Einsatzzeit nur tage / Stunden anzeigen wenn nicht null
    //TODO: Buttons für einlesen... in darkmode integrieren
    //TODO: Icon auswahl für Führungsstellenname und favicon

    // Funktion zum Ausführen der AJAX-Anfrage
function loadJSON() {
    // Erstelle eine neue XMLHttpRequest-Instanz
    var xhr = new XMLHttpRequest();
    xhr.open('GET', './einsatzmonitor.php?json', true);
    xhr.responseType = 'json';
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            // Speichere die empfangene JSON-Antwort in einer Variablen
            if (myData && myData.hasOwnProperty('einheiten'))
            {
                einheiten = myData['einheiten'];
            }else{
                einheiten = [];
            }
            myData = xhr.response;
            if (!myData || myData.hasOwnProperty('getSeconds') == false)
            {
                myData['getSeconds'] = "0";
                sendKeyValue('getSeconds', "0");
            }
            if (!myData || myData.hasOwnProperty('darkmode') == false)
            {
                myData['darkmode'] = "1";
                sendKeyValue('darkmode', "1");
            }
            if (!myData || myData.hasOwnProperty('noDTG') == false)
            {
                myData['noDTG'] = "0";
                sendKeyValue('noDTG', "0");
            }
            if (!myData || myData.hasOwnProperty('einheiten') == false)
            {
                myData['einheiten'] = [];
                sendKeyValue('einheiten', myData['einheiten']);
                aktualisiereEinheitenListe();
                aktualisiereGesamtstaerke();
                console.log("Einheitenliste wurde erstellt");
            } else if (!arraysEqual(einheiten, myData['einheiten']))
            {
                console.log("Einheitenliste war ungleich");
                aktualisiereEinheitenListe();
                aktualisiereGesamtstaerke();
            }           

            SetzeEigenerFunkrufname();
            berechneUndZeigeEinsatzzeit();
            if (myData['darkmode'] == '1') {
                aktiviereDarkMode();
            } else {
                deaktiviereDarkMode();
            }
        } else {
            console.error('Die Anfrage war nicht erfolgreich. Status:', xhr.status);
        }
    };
    xhr.onerror = function() {
        console.error('Ein Fehler ist bei der Anfrage aufgetreten.');
    };
    xhr.send();
}

function sendKeyValue(key, value) {
    var data = {};
    data[key] = value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', './einsatzmonitor.php?json', true);
    xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            console.log('Daten erfolgreich gesendet:', xhr.responseText);
            //myData = xhr.responseText;
        } else {
            console.error('Die Anfrage war nicht erfolgreich. Status:', xhr.status);
        }
    };
    xhr.onerror = function() {
        console.error('Ein Fehler ist bei der Anfrage aufgetreten.');
    };
    xhr.send(JSON.stringify(data));
}

function arraysEqual(arr1, arr2) {
    if (arr1.length == 0 && arr2.length == 0 ) {
        return true;
    }
    if (arr1.length !== arr2.length) {
        return false;
    }
    for (var i = 0; i < arr1.length; i++) {
        if (arr1[i] !== arr2[i]) {
            return false;
        }
    }
    return true;
}

</script>

</html>

<?php } ?>