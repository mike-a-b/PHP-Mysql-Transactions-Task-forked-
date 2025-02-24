const submit_btn = document.getElementById("submit");
const data_table = document.getElementById("data");

// send ajax request on click button and get response
submit_btn.onclick = function (e) {
  e.preventDefault();
  data_table.style.display = "block";

  let ajaxRequest = new XMLHttpRequest();

  ajaxRequest.onreadystatechange = function () {
    if (ajaxRequest.readyState == 4) {
      // ajax good , check state
      if (ajaxRequest.status == 200) {
        // get users transactions
        //   JSON.parse(ajaxRequest.responseText)
        console.log("ajax ok, response =" + ajaxRequest.responseText);
      }
    }
  }

  let user =  document.getElementById("user");
  ajaxRequest.open("POST", "data.php", true);
  console.log("user value: " + user.value);
  ajaxRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  ajaxRequest.send("user="+user.value);
}
