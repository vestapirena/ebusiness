<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/c/CodingLabYT-->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title>Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <link rel="stylesheet" href="my_container.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <div class="container-fluid">
      <div class="my_container">
        <form class="row g-3">
          <div class="col-md-6">
            <label for="product_name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="product_name">
          </div>
          <div class="col-md-3">
            <label for="product_price" class="form-label">Product Price</label>
            <input type="text" class="form-control" id="product_price">
          </div>
          <div class="col-md-3">
            <label for="product_amount" class="form-label">Product Amount</label>
            <input type="text" class="form-control" id="product_amount">
          </div>
          <div class="col-12">
            <button type="button" class="btn btn-light btn-sm" onclick="save();" id="btnSave">Save</button>
            <button type="button" class="btn btn-light btn-sm" onclick="clear_function();" id="btnSave">Clear</button>
          </div>
          <input type="hidden" id="id" value="0" />
        </form>
        <br>

        <table class="table table-bordered" id="tabla">
            <thead class="table-dark ">
              <tr>
                <td>Product Name</td>
                <td>Product Price</td>
                <td>Product Amount</td>
                <td class="text-center" colspan="2">Actions</td>
              </tr>
            </thead>
            <tbody id="datatable">               
            </tbody>
          </table>
      <div>
    </div>

    <script>

      let retrieve = async (success) => {

        if(success==1) {
          before_data();
          disable_data();
          await new Promise(resolve => setTimeout(resolve, 1000));
        }
        let request = new XMLHttpRequest();
        request.onreadystatechange = function() {
          if(request.readyState === 4) {
            if(request.status === 200) {
              let response = JSON.parse(request.responseText);
              let status = response.status;
              // Ok
              clean_data();
              process_data(response);
              enable_data();
              after_data();

            } else if(request.status === 403) {
              let error = 'An error occurred during your request: ' +  request.status + ' ' + request.statusText;
              console.log("error " + error);
            } else {
              let error = 'An error occurred during your request: ' +  request.status + ' ' + request.statusText;
              console.log("error " + error);
            } 
          }
        }

        request.open('GET', 'http://localhost/ebusiness/v1/products', true);
        request.setRequestHeader ('Content-Type', 'application/x-www-form-urlencoded'); 
        request.send('');
      };

      let save = async () => {
        before_data();
        disable_data();
        await new Promise(resolve => setTimeout(resolve, 1000));
        let request = new XMLHttpRequest();
        request.onreadystatechange = function() {
          if(request.readyState === 4) {
            if(request.status === 200) {
              let response = JSON.parse(request.responseText); 
              let status = response.status;
              if(status==1){
                // Ok
                clean_data();
                retrieve(2);
              } else {
                //Error
                let status_message = response.status_message;
                alert(status_message);
                enable_data();
                after_data();
              }
              
            } else if(request.status === 403) {
              let error = 'An error occurred during your request: ' +  request.status + ' ' + request.statusText;
              console.log("error " + error);
            } else {
              let error = 'An error occurred during your request: ' +  request.status + ' ' + request.statusText;
              console.log("error " + error);
            } 
          }
        }
        let json = new Object();
        json.id = document.getElementById('id').value;
        json.product_name = document.getElementById('product_name').value;
        json.product_price  = document.getElementById('product_price').value;
        json.product_amount = document.getElementById('product_amount').value;
        request.open('POST', 'http://localhost/ebusiness/v1/products');
        request.setRequestHeader ('Content-Type', 'application/json;charset=UTF-8'); 
        request.send(JSON.stringify(json));
      };

      let search = async (id) => {
        before_data();
        disable_data();
        clean_data();
        await new Promise(resolve => setTimeout(resolve, 1000));
        let request = new XMLHttpRequest();
        request.onreadystatechange = function() {
          if(request.readyState === 4) {
            if(request.status === 200) {
              let response = JSON.parse(request.responseText); //console.log(request.responseText);
              let status = response.status;
              // Ok
              document.getElementById('id').value = response[0].id;
              document.getElementById('product_name').value = response[0].product_name;
              document.getElementById('product_price').value = response[0].product_price;
              document.getElementById('product_amount').value = response[0].product_amount;
              enable_data();
              after_data();
              update_function();
            } else if(request.status === 403) {
              let error = 'An error occurred during your request: ' +  request.status + ' ' + request.statusText;
              console.log("error " + error);
            } else {
              let error = 'An error occurred during your request: ' +  request.status + ' ' + request.statusText;
              console.log("error " + error);
            } 
          }
        }

        request.open('GET', 'http://localhost/ebusiness/v1/products/'+id, true);
        request.setRequestHeader ('Content-Type', 'application/x-www-form-urlencoded'); 
        request.send('');
      };

      let update = async () => {
        before_data();
        disable_data();
        await new Promise(resolve => setTimeout(resolve, 1000));
        let request = new XMLHttpRequest();
        request.onreadystatechange = function() {
          if(request.readyState === 4) {
            if(request.status === 200) { console.log(request);
              let response = JSON.parse(request.responseText);
              let status = response.status;
              if(status==1){
                // Ok
                clean_data();
                retrieve(2);
              } else {
                //Error
                let status_message = response.status_message;
                alert(status_message);
                enable_data();
                after_data();
              }

            } else if(request.status === 403) {
              let error = 'An error occurred during your request: ' +  request.status + ' ' + request.statusText;
              console.log("error " + error);
            } else {
              let error = 'An error occurred during your request: ' +  request.status + ' ' + request.statusText;
              console.log("error " + error);
            } 
          }
        }
        let json = new Object();
        let id = document.getElementById('id').value;
        json.product_name = document.getElementById('product_name').value;
        json.product_price  = document.getElementById('product_price').value;
        json.product_amount = document.getElementById('product_amount').value;
        request.open('PUT', 'http://localhost/ebusiness/v1/products/'+id);
        request.setRequestHeader ('Content-Type', 'application/json;charset=UTF-8'); 
        request.send(JSON.stringify(json));
      };

      let delete_data = async (id) => {
        before_data();
        disable_data();
        clean_data();
        await new Promise(resolve => setTimeout(resolve, 1000));
        let request = new XMLHttpRequest();
        request.onreadystatechange = function() {
          if(request.readyState === 4) {
            if(request.status === 200) {
              let response = JSON.parse(request.responseText);
              let status = response.status;
              if(status==1){
                // Ok
                retrieve(2);
              } else {
                //Error
                let status_message = response.status_message;
                alert(status_message);
                enable_data();
                after_data();
              }
              
            } else if(request.status === 403) {
              let error = 'An error occurred during your request: ' +  request.status + ' ' + request.statusText;
              console.log("error " + error);
            } else {
              let error = 'An error occurred during your request: ' +  request.status + ' ' + request.statusText;
              console.log("error " + error);
            } 
          }
        }

        request.open('DELETE', 'http://localhost/ebusiness/v1/products/'+id, true);
        request.setRequestHeader ('Content-Type', 'application/x-www-form-urlencoded'); 
        request.send('');
      };

      process_data = (elements) => {
        let html = ``;
        let count = 0;
        for(const key in elements) {
          html +=  `
            <tr>
              <td>${elements[key].product_name}</td>
              <td>$${elements[key].product_price}</td>
              <td>${elements[key].product_amount}</td>
              <td><button type="button" class="btn btn-light btn-sm" onclick="search(${elements[key].id});" >Update</button></td>
              <td><button type="button" class="btn btn-light btn-sm" onclick="confirm_action(${elements[key].id});" >Delete</button></td>
            </tr>
          `;
          count++;
        }
        html +=  ``;
        document.getElementById('datatable').innerHTML = html; 
      };

      let confirm_action = (id) => {
          let response = confirm("Are you sure you want to do that?");
          if (response) {
            delete_data(id);
          } 
      }

      let clean_data = () => {
        document.getElementById('id').value = "";
        document.getElementById('product_name').value = "";
        document.getElementById('product_price').value = "";
        document.getElementById('product_amount').value = "";
      }

      let before_data = () => {
        document.getElementById('btnSave').innerHTML = "<div class='spinner-border spinner-border-sm'></div>";
      }

      let after_data = () => {
        document.getElementById('btnSave').innerHTML = "Save";
      }

      let disable_data = () => {
        document.getElementById('product_name').disabled = true;
        document.getElementById('product_price').disabled = true;
        document.getElementById('product_amount').disabled = true;
        document.getElementById('btnSave').disabled = true;
      }

      let enable_data = () => {
        document.getElementById('product_name').disabled = false;
        document.getElementById('product_price').disabled = false;
        document.getElementById('product_amount').disabled = false;
        document.getElementById('btnSave').disabled = false;
      }

      let update_function = () => {
        document.getElementById('btnSave').setAttribute( "onClick", "update()" );
        document.getElementById('btnSave').innerHTML = "Update";
      }

      let clear_function = () => {
        after_data();
        clean_data();
        document.getElementById('btnSave').setAttribute( "onClick", "save()" );
        document.getElementById('btnSave').innerHTML = "Save";
      }

      retrieve(1);
    </script>
  </body>
</html>