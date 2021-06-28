// ACHTERGROND SWITCH
    /* UITLEG:
    styleSlider(); is de function waarmee je de styles switched.
    styleSlider("nintendo"); zorgt voor de nintendo style word gebruikt.
    styleSlider("true"); zorgt voor de XBOX style.
    styleSlider("false");  zorgt voor de PS style.
    */
    function styleSlider(extra) {
        let t = document.getElementById("switchText");
        let c = document.getElementById("sliderCheck");
        c.checked = false;
        let p = document.getElementById("psAchtergrond");
        let x = document.getElementById("xboxAchtergrond");
        let n = document.getElementById("nintendoAchtergrond");
        let s = document.getElementById("slider");
        let els = document.getElementsByClassName("submitKnop");
        let fik = document.getElementById("fileInputLabel");
        //let sk = document.getElementById("submitKnop");
        if (extra == 'nintendo') {
            t.innerHTML = "Nintendo";
            s.classList.add("Nintendo");
            s.classList.remove("norm");
            for(let i = 0; i < els.length; i++){els[i].style.color = "white";els[i].style.background = "#E70013";}
            if (fik!=null){fik.style.backgroundColor = "#E70013"};
            x.style.opacity = 0;
            p.style.opacity = 0;
            n.style.opacity = 1;
            return
        } 
        s.classList.remove("nintendo");
        s.classList.add("norm");
        n.style.opacity = 0;
        if (x.style.opacity == 1 || extra == 'false') {
            t.innerHTML = "Playstation";
            c.checked = false;
            setCookie("achtergrondSlider", false, 100);
            //sk.style.background = "#006FCD";
            if (fik!=null){fik.style.backgroundColor = "#006FCD";}
            for(let i = 0; i < els.length; i++){els[i].style.color = "white"; els[i].style.background = "#006FCD";}
            x.style.opacity = 0;
            p.style.opacity = 1;
        } else {
            for(let i = 0; i < els.length; i++){els[i].style.color = "white"; els[i].style.background = "#1DB900";}
            //sk.style.background = "#1DB900";
            if (fik!=null){fik.style.backgroundColor = "#1DB900"};

            t.innerHTML = "XBOX";
            c.checked = true;
            setCookie("achtergrondSlider", true, 100);
            p.style.opacity = 0;
            x.style.opacity = 1;
        }
    }


    // Cookie functies om cookies te maken
    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    } 
    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
            c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
            }
        }
        return "";
        }
        // ^^^^^ Cookie functies om cookies te maken ^^^^^





function checkOverflow(el)
{
    var curOverflow = el.style.overflow;

    if ( !curOverflow || curOverflow === "visible" )
        el.style.overflow = "hidden";

    var isOverflowing = el.clientWidth < el.scrollWidth
        || el.clientHeight < el.scrollHeight;

    el.style.overflow = curOverflow;

    return isOverflowing;
}

function errorr(error, text) {
    console.log(text)
    console.log(error)
    if (error == true) {
        document.getElementById('errorDiv').classList.remove('success');
        document.getElementById('errorText').innerHTML = text;
    } else if(error == false) {
        document.getElementById('errorText').innerHTML = text;
    }
}

function kill() {

    let verwijder=confirm("weet je zeker dat je dit weetje wilt verwijderen?");

    if (verwijder == true) {
        return true;
    } else {
        return false;
    }
}

function plaatje_weetje(extent){
    console.log(extent);
    let ding = document.getElementById(extent);
    if(ding.style.height=='100%'){
        ding.style.height="200px";
    }else{
        ding.style.height="100%";

    }
    
}