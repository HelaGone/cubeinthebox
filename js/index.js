document.addEventListener("DOMContentLoaded", function(event){
  console.log("DOM Loaded");

  if('serviceWorker' in navigator){
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('./sw.js').then(registration => {
        alert("A service woker was installed successfully");
      }, err => {
        console.error("Failed: ", err);
      })
    })
  }

}); //Document Ready
