const epshow = () =>{
    window.location.href = "ep1.php";
}
const openmyaccount = () =>{
  window.location.href = "../myaccount.php";
}

const eps1 = () => {
    
    let dp = document.getElementById("dpositpay");
    let dayearn = document.getElementById("dayearn");
    let valid = document.getElementById("validation");
    let pname = document.getElementById("plane_name");

    pname.innerHTML = "Starter Plan";
    dp.innerHTML = "Deposit: 400 RS";           
    dayearn.innerHTML = "Daily Earning: 25 RS";
    valid.innerHTML = "Valid for: 180 Days";
}
const ep2 = () => {
    
    let dp = document.getElementById("dpositpay");
    let dayearn = document.getElementById("dayearn");
    let valid = document.getElementById("validation");
    let pname = document.getElementById("plane_name");

    pname.innerHTML = "Basic Plane";
    dp.innerHTML = "Deposit: 800 RS";           
    dayearn.innerHTML = "Daily Earning: 50 RS";
    valid.innerHTML = "Valid for: 180 Days";

   
}
const ep3 = () => {
    
    let dp = document.getElementById("dpositpay");
    let dayearn = document.getElementById("dayearn");
    let valid = document.getElementById("validation");
    let pname = document.getElementById("plane_name");

    pname.innerHTML = "BronzePlane";
    dp.innerHTML = "Deposit: 1600 Rs";           
    dayearn.innerHTML = "Daily Earning: 100 RS";
    valid.innerHTML = "Valid for: 180 Days";

   
}
const ep4 = () => {
    
    let dp = document.getElementById("dpositpay");
    let dayearn = document.getElementById("dayearn");
    let valid = document.getElementById("validation");
    let pname = document.getElementById("plane_name");

    pname.innerHTML = "Ultimate Plane";
    dp.innerHTML = "Deposit: 6400 RS";           
    dayearn.innerHTML = "Daily Earning: 400 RS";
    valid.innerHTML = "Valid for: 180 Days";

   
}
const ep5 = () => {
    
    let dp = document.getElementById("dpositpay");
    let dayearn = document.getElementById("dayearn");
    let valid = document.getElementById("validation");
    let pname = document.getElementById("plane_name");

    pname.innerHTML = "Standerd Plane";
    dp.innerHTML = "Deposit:12800 RS ";           
    dayearn.innerHTML = "Daily Earning: 800 RS";
    valid.innerHTML = "Valid for: 180 Days";

   
}
const openplan = () =>{
     window.location.href = "./homepage/home.php";
}
 let invitebt = document.getElementById("cd1");
  let invitediv = document.getElementById("invitetext");
  let dailydiv = document.getElementById("dailyearn");
  let withdraw = document.getElementById("withdraw");
  let wb = document.getElementById("withdrawbutton");
  let earnbt = document.getElementById("earnbtn");

  if (invitebt) {
    invitebt.addEventListener("click", function () {
      remclassfrominvite();
    });
  }

  const remclassfrominvite = () => {
    invitediv.classList.remove("hidden");
    dailydiv.classList.add("dnone");
    withdraw.classList.add("dnone");
  }

  const remclassfromdailyearn = () => {
    dailydiv.classList.remove("dnone");
    invitediv.classList.add("hidden");
    withdraw.classList.add("dnone");
  }

  if (earnbt) {
    earnbt.addEventListener("click", function () {
      remclassfromdailyearn();
    });
  }

  const remclassfromwithdraw = () => {
    withdraw.classList.remove("dnone");
    invitediv.classList.add("hidden");
    dailydiv.classList.add("dnone");
  }

  if (wb) {
    wb.addEventListener("click", function () {
      remclassfromwithdraw();
    });
  }
  
  const copyBtn = document.getElementById("buttonofcopyaccountno");
const accountNo = document.getElementById("accountno");

if (copyBtn && accountNo) {
  copyBtn.addEventListener("click", () => {
    navigator.clipboard.writeText(accountNo.innerText)
      .then(() => alert("Copied: " + accountNo.innerText));
  });
}


  
  const copyBtnref = document.getElementById("buttonofcopyrefcode");
const refcode = document.getElementById("refcode");

if (copyBtnref && refcode) {
  copyBtnref.addEventListener("click", () => {
    navigator.clipboard.writeText(refcode.innerText)
      .then(() => alert("Copied: " + refcode.innerText));
  });
}