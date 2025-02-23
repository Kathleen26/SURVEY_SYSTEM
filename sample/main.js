function callDate(){
    var currentDate=(new Date()).toDateString(); 
    document.getElementById('Time').innerHTML=`${currentDate}`;
  }

  function callTime(){
    var currentTime=(new Date()).toLocaleTimeString(); 
    document.getElementById('Date').innerHTML=`${currentTime}`;
  }

setInterval(function(){callDate(), callTime()}, 1000);



