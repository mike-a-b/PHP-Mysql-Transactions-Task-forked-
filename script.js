const submit_btn = document.getElementById("submit");
const data_table = document.getElementById("data");
const datatbl = document.getElementById("data_tbl")
function showProps(obj, objName) {

}

// send ajax request on click button and get response
submit_btn.onclick = function (e) {
  e.preventDefault();
  data_table.style.display = "block";

  let ajaxRequest = new XMLHttpRequest();
  let h2 = data_table.querySelector("h2");
  ajaxRequest.onreadystatechange = function () {
    if (ajaxRequest.readyState == 4) {
      // ajax good , check state
      if (ajaxRequest.status == 200) {
        // get users transactions

        let balances = JSON.parse(ajaxRequest.responseText);
        console.log("balances = " + balances);
        const Months = [ 'January','Februarry','March','April','May','June','July',
          'August','September','October','November','December'];
        if(balances !== 0 ) {
          console.log("balances length: " + balances.length);
          var result = "";
          for (var i in balances) {
            if (balances.hasOwnProperty(i)) {
              result += i + " = " + balances[i] + "\n";
              let table_tr = document.createElement("tr");
              let table_td1 = document.createElement("td");
              let table_td2 = document.createElement("td");
              table_td1.innerHTML = Months[i-1] ;
              balances[i] ? table_td2.innerHTML = balances[i] : table_td2.innerHTML = "0" ;
              table_tr.appendChild(table_td1);
              table_tr.appendChild(table_td2);
              data_tbl.appendChild(table_tr);
            }
          }
          console.log("test " + result);
          h2.innerHTML = "Transactions of user" ;
          // console.log("Transactions of " + balances);
          // console.log("typeof balances = " + typeof balances);
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
