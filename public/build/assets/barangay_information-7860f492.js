const e=document.querySelector("#logo_input");e&&e.addEventListener("change",t=>{document.querySelector("#img_logo").src=URL.createObjectURL(t.target.files[0])});
