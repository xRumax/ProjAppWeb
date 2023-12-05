function odliczanieDaty(){
    var x = new Date();
    var day = x.getDate();
    if(day < 10) day = "0" + day;
    const monthNames=["Styczeń","Luty","Marzec","Kwiecień","Maj","Czerwiec","Lipiec","Sierpień","Wrzesień","Październik","Listopad","Grudzień"];
    var month = monthNames[x.getMonth()];
    var year = x.getFullYear()

    document.getElementById("date").innerHTML = day + " " + month + " " + year;
}

function odliczanieCzasu(){
    var x = new Date();

    var hour = x.getHours();
    if(hour < 10) hour = "0" + hour;
    var minute = x.getMinutes();
    if(minute < 10) minute = "0" + minute;
    var second = x.getSeconds();
    if(second < 10) second = "0" + second;

    document.getElementById("zegar").innerHTML = hour + ":" + minute + ":" + second;
    setTimeout("odliczanieCzasu()", 1000); 
}