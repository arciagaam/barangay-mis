const t=document.querySelector("#birth_date"),l=e=>{const a=Date.now()-new Date(e).getTime(),n=new Date(a);return n.getUTCFullYear()-1970<0?0:Math.abs(n.getUTCFullYear()-1970)};if(t){const e=document.querySelector("#age");window.addEventListener("load",()=>{e.value=t.value!==""?l(t.value):0}),t.addEventListener("change",a=>{e.value=l(a.target.value)})}
