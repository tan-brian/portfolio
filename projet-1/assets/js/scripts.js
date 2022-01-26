function toggleMenu(id) {
    let nav = document.querySelector(".nav");
    if (id === 1) {
      nav.style.left = "-2%";
    } else {
      nav.style.left = "-120%";
    }
    
  }

  window.addEventListener('resize', ()=> {
    if(window.innerWidth > 1150)
    toggleMenu(1);
    else
    toggleMenu(0);
  })
  
let login = document.querySelector('.login-btn'),
image = login.querySelector('i');


login.addEventListener('mouseover', () =>{
  if(window.innerWidth> 1500)
image.style.display = "inline";
})

login.addEventListener('mouseout', () =>{
image.style.display = "none";
})


let likeBtns = document.querySelectorAll('.like-button i');
    
likeBtns.forEach(btn => {

    btn.addEventListener('click', ()=>{
      
      
     let idEnchere = btn.dataset.jsEnchere,
         commande;
         
         if(btn.classList.contains('fa-heart')){
         
           commande='delete';
         }else {
          
           commande='insert';
         }
         
      let myInit = { 
          method: 'post',
          headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
          },
          body: 'idUsager=' + idUsager + '&idEnchere=' + idEnchere + '&commande=' + commande
      };

      let myRequest = new Request('/projet-1/callAsync/RequeteAsync.php', myInit);

      fetch(myRequest)
          .then(function(response) {

              return response;
          })
          .then(function(data) {

            btn.classList.toggle('fa-heart');
            btn.classList.toggle('fa-heart-o');
            
          })
          .catch(function(error) {
              console.log(`Il y a eu un problème avec l'opération fetch: ${error.message}`);
          });

            
        })
})

 //https://www.w3schools.com/howto/howto_js_countdown.asp

 let timers = document.querySelectorAll('[data-js-timer]');

 timers.forEach(timer => {
   if(timer != null) {
       var countDownDate = Date.parse(timer.textContent);
   
   // Update the count down every 1 second
   var x = setInterval(function() {
   
     // Get today's date and time
     var now = new Date().getTime();
    console.log(new Date())
     // Find the distance between now and the count down date
     var distance = countDownDate - now;
   
     // Time calculations for days, hours, minutes and seconds
     var days = Math.floor(distance / (1000 * 60 * 60 * 24));
     var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
     var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
     var seconds = Math.floor((distance % (1000 * 60)) / 1000);
   
     // Display the result in the element with id="demo"
     timer.innerHTML = days + "j " + hours + "h "
     + minutes + "m " + seconds + "s ";
    
   }, 1000);
       }
 })

