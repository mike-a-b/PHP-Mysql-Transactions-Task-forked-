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
        let balances = JSON.parse(ajaxRequest.responseText);
        if(balances !== 0 ) {
          balances.forEach(function (item, i, balances) {
            let table_tr = document.createElement("tr");
            let table_td1 = document.createElement("td");
            let table_td2 = document.createElement("td");
            table_td1.innerHTML = i;
            table_td2.innerHTML = item;
            table_tr.appendChild(table_td1);
            table_tr.appendChild(table_td2);
            data_table.appendChild(table_tr);
          });
          console.log("test response =" + JSON.parse(ajaxRequest.responseText));
        }
      }
    }
  }

  let user =  document.getElementById("user");
  ajaxRequest.open("POST", "data.php", true);
  console.log("user value: " + user.value);
  ajaxRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  ajaxRequest.send("user=" + user.value);
}
