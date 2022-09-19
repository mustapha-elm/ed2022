
const countdownDays = document.getElementById("countdown-d");
const countdownHours = document.getElementById("countdown-h");
const countdownMin = document.getElementById("countdown-m");
const countdownSec = document.getElementById("countdown-s");


function Rebour() {
var date1 = new Date();
var date2 = new Date ("Sep 30 21:54:00 2023"); // Date et heure de l'événement
var sec = (date2 - date1) / 1000; // Temps donné en millièmes de seconde
var n = 24 * 3600; //nombre de secondes dans un jour
if (sec > 0) {
    j = Math.floor (sec / n);
    h = Math.floor ((sec - (j * n)) / 3600);
    mn = Math.floor ((sec - ((j * n + h * 3600))) / 60);
    sec = Math.floor (sec - ((j * n + h * 3600 + mn * 60)));

    countdownDays.innerHTML = j;
    countdownHours.innerHTML = h;
    countdownMin.innerHTML = mn;
    countdownSec.innerHTML = sec;
    //window.status = "Temps restant : " + j +" jours, "+ h +" h "+ mn +" min et "+ sec + " sec ";
}
else if (Math.abs(sec) < (3 * n)) { // Durée de l'événement
    countdownDays.innerHTML = 0;
    countdownHours.innerHTML = 0;
    countdownMin.innerHTML = 0;
    countdownSec.innerHTML = 0;
    
}
else {
    countdownDays.innerHTML = 0;
    countdownHours.innerHTML = 0;
    countdownMin.innerHTML = 0;
    countdownSec.innerHTML = 0;
   
}

setTimeout ("Rebour()", 1000);
}

Rebour(); 


