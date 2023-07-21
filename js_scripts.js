
$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault();

        let createnewaccount = false;
        
        if ($('input:checkbox[name=newAccountCheckBox]').is(':checked')) {
            createnewaccount = true;
        }
        
        let login = $("#login").val();
        let pass = $("#pass").val();
        let confirmpass = $("#confirmPass").val();

        let firstname = $("#firstName").val();
        let lastname = $("#lastName").val();
        let country = $("#country").val();
        let address = $("#address").val();
        let postcode = $("#postCode").val();
        let city = $("#city").val();
        let telephone = $("#telephone").val();
        let deliverymethod = $("input:radio[name=deliveryMethodRadio]:checked").val();
        let paymentmethod = $("input:radio[name=paymentMethodRadio]:checked").val();
        let comment = $("#orderComment").val();
        let getnewsletter = false;
        let acceptregulations = false;

        if ($('input:checkbox[name=getNewsletterCheckBox]').is(':checked')) {
            getnewsletter = true;
        }
        if ($('input:checkbox[name=acceptRegulationsCheckBox]').is(':checked')) {
            acceptregulations = true;
        }
        
        validation: {

            if(createnewaccount){
                if(!/[a-zA-Z0-9 ]{5,32}/.test(login)){
                    showErrorMessage("Login nie spełnia wymagań!");
                    break validation;
                }
                else if(!/[a-zA-Z0-9_]{8,15}/.test(pass)){
                    showErrorMessage("Hasło nie spełnia wymagań!");
                    break validation;
                }
                else if(pass !== comfirnmass){
                    showErrorMessage("Hasła się różnią!");
                    break validation;
                }
            }

            if(!/[a-zA-Z0-9 ]{1,32}/.test(firstname)){
                showErrorMessage("Imię nie spełnia wymagań!");
                break validation;
            }
            else if(!/[a-zA-Z0-9 ]{1,32}/.test(lastname)){
                showErrorMessage("Nazwisko nie spełnia wymagań!");
                break validation;
            }
            else if(address.length < 5){
                showErrorMessage("Adres nie spełnia wymagań!");
                break validation;
            }
            else if(!/^\d{2}-\d{3}$/.test(postcode)){
                showErrorMessage("Kod pocztowy nie spełnia wymagań!");
                break validation;
            }
            else if(city.length < 4){
                showErrorMessage("Miasto nie spełnia wymagań!");
                break validation;
            }
            else if(!/^(?:\+48\s?)?\d{9}$/.test(telephone)){
                showErrorMessage("Numer telefonu nie spełnia wymagań!");
                break validation;
            }
            else if(deliverymethod === undefined){
                showErrorMessage("Proszę wybrać metodę dostawy");
                break validation;
            }
            else if(paymentmethod === undefined){
                showErrorMessage("Proszę wybrać metodę płatności");
                break validation;
            }
            else if(acceptregulations == false){
                showErrorMessage("Zapoznanie się z regulaminem jest obowiązkowe");
                break validation;
            }
            
            $.post("process.php", 
                {
                    createnewaccount:createnewaccount,
                    login:login,
                    pass:pass,
                    confirmpass:confirmpass,
                    firstname:firstname,
                    lastname:lastname,
                    country:country,
                    address:address,
                    postcode:postcode,
                    city:city,
                    telephone:telephone,
                    deliverymethod:deliverymethod,
                    paymentmethod:paymentmethod,
                    comment:comment,
                    getnewsletter:getnewsletter,     
                    acceptregulations:acceptregulations,
                }, 
                function(data, status){
                    //console.log(data);
                    const returnedData = JSON.parse(data);

                    //console.log(returnedData);
                    if(returnedData.isValidationSucceed){
                        showOrderInfo(returnedData.orderNumber);
                    }
                    else{
                        showErrorMessage(returnedData.msg);
                    }
                })      
        }       
    });
})

function showErrorMessage(msg){
    $('.errorMessage').text(msg);

    $('.validationInfo')
        .css("display", "flex")
        .hide()
        .fadeIn('normal')
        .delay(1500)
        .fadeOut();
}

function showOrderInfo(orderNumber){
    $(".mainContent").fadeOut(300, function() {
        $(".orderNumber").text("Twój numer zamówienia: " + orderNumber);
        $(".orderCompleteInfo").css("display", "flex");
    });
}

function showHideLoginFields(){
    let newAccountCheckbox = document.getElementById("newAccountCheckBox");

    let loginField = document.getElementById("login");
    let passField = document.getElementById("pass");
    let confirmPassField = document.getElementById("confirmPass");

    if(newAccountCheckbox.checked){
        loginField.style.display = "block";
        passField.style.display = "block";
        confirmPassField.style.display = "block";
    }else{
        loginField.style.display = "none";
        passField.style.display = "none";
        confirmPassField.style.display = "none";
    }   
}

function clearAddressData(){
    let newAddressCheckBox = document.getElementById("differentAddressCheckBox");

    let countryField = document.getElementById("country");
    let addressField = document.getElementById("address");
    let postCodeField = document.getElementById("postCode");
    let cityField = document.getElementById("city");

    if(newAddressCheckBox.checked){
        countryField.value = 'poland';
        addressField.value = "";
        postCodeField.value = "";
        cityField.value = "";
    }   
}

function matchPaymentMethods(callerMethod){

    let paymentMethod1 = document.getElementsByClassName("paymentMethod1")[0];
    let paymentMethod2 = document.getElementsByClassName("paymentMethod2")[0];
    let paymentMethod3 = document.getElementsByClassName("paymentMethod3")[0];

    switch(callerMethod){
        case "1":
            paymentMethod1.style.display = "flex";
            paymentMethod2.style.display = "none";
            paymentMethod3.style.display = "flex";
            break;
        case "2":
            paymentMethod1.style.display = "none";
            paymentMethod2.style.display = "flex";
            paymentMethod3.style.display = "none";
            break;
        case "3":
            paymentMethod1.style.display = "flex";
            paymentMethod2.style.display = "flex";
            paymentMethod3.style.display = "none";
            break;
    }
}