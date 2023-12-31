let orderTotalPrice = 0.0;
let orderPartialPrice = 0.0;
let deliveryPrices = [];
let deliveryMethod = 0;
const deliveryMethods = [
    { id: 1 },
    { id: 2 },
    { id: 3 }
];
const items = [
    { id: 1, amount: 1 },
    { id: 2, amount: 2 },
    { id: 3, amount: 5 }
];
let userAddress = { 
    country: "",
    steet: "", 
    postCode: "",
    city: "" 
};

$(document).ready(function () {
    loadDeliveryMethods();
    loadItems();

    $("#userDataForm").submit(function (event) {
        submitUserDataForm(event);
    });

    $("#userLoginForm").submit(function (event) {
        submitUserLoginForm(event);
    });
})

function toggleDiscountCodeContainer(){
    $(".activateDiscountCodeBtn").toggle();
    $("#discountCode").toggle();
}

function activateDiscountCode(){

    $.get("check_discount_codes.php",
    { 
        "code":$("#discountCode").val()
    },
    function(data)
    {
        const returnedData = JSON.parse(data);

        if(returnedData.is_active == "true"){
            $("#discountCode").prop('disabled', true);
            let button = $(".activateDiscountCodeBtn");
    
            button.css("color", "rgb(168,159,143)");
            button.css("border-color", "rgb(168,159,143)");
            button.text("Kod aktywowany");
            button.prop( "onclick", null );

            let discount = (orderPartialPrice  * (parseInt(returnedData.discount_percentage) / 100));
            changeOrderPartialPrice(-discount);
            changeOrderTotalPrice();
            
            let discountLabel = $("<p></p>");
            discountLabel.css("margin-top", "5px");
            discountLabel.css("text-align", "right");
            discountLabel.css("color", "red");
            discountLabel.text("Z rabatem  " + returnedData.discount_percentage + "%");
            discountLabel.insertAfter(".totalPrice");

            showSuccessMessage("Rabat został naliczony!");
        }
        else{
            showErrorMessage("Wprowadzony kod jest nieaktywny!");
        }

    });
}

function loadDeliveryMethods(){

    $.get("load_delivery_methods.php",
    { 
        "delivery_methods":JSON.stringify(deliveryMethods)
    },
    function(data)
    {
        const returnedData = JSON.parse(data);

        if(returnedData.isLoadingSucceed){
            $(returnedData.delivery_method_id_list).each(function(i, e) {
                var j = returnedData.delivery_method_id_list.length - 1 - i;
    
                $(
                    '<div class="deliveryMethod"><div>'+
                    '<input type="radio" id="deliveryMethod' + returnedData.delivery_method_id_list[j] + 
                    '" name="deliveryMethodRadio" value="' + returnedData.delivery_method_id_list[j] + 
                    '" form="userDataForm" onclick="matchPaymentMethods(' + returnedData.delivery_method_id_list[j] + 
                    ')"><img src="delivery_method_images/' + returnedData.delivery_method_file_name_list[j] + 
                    '" alt="Metoda dostawy" width="50" height="30"><label for="deliveryMethod' + 
                    returnedData.delivery_method_id_list[j] + '">' + returnedData.delivery_method_name_list[j] + 
                    '</label></div><p>' + returnedData.delivery_method_price_list[j] + ' zł</p></div>'
    
                ).insertAfter(".deliveryMethodLabel");
    
                deliveryPrices[j] = returnedData.delivery_method_price_list[j];
            }); 
        }          
    });
}

function loadItems(){

    $.get("load_items.php",
    { 
        "items":JSON.stringify(items)
    },
    function(data)
    {
        const returnedData = JSON.parse(data);

        if(returnedData.isLoadingSucceed){
            $(returnedData.item_id_list).each(function(i, e) {
                $(
                    '<div class="productInfo"><div class="productImg">'+
                    '<img src="item_images/' + returnedData.item_file_name_list[i] + '" alt="item"></div>'+
                    '<div class="productDetails"><p class="productName">' + returnedData.item_name_list[i] +    
                    '</p> <p>Ilość: ' + returnedData.item_amount_list[i] + '</p></div>'+
                    '<div class="productPrice"><p>' + returnedData.item_price_list[i] + ' zł</p></div></div>'
                ).insertBefore(".orderPriceContainer");
            });    
            
            let priceSumm = 0.0;
    
            for(let i = 0; i < returnedData.item_id_list.length; i++){
                priceSumm += (parseFloat(returnedData.item_price_list[i]) * parseInt(returnedData.item_amount_list[i]));
            }
    
            changeOrderPartialPrice(priceSumm);
            changeOrderTotalPrice();
        }
        
    });
}

function changeOrderPartialPrice(value){
    orderPartialPrice += parseFloat(value);
    orderPartialPrice = Number(orderPartialPrice.toFixed(2));

    $(".partialPrice").find("p").eq(1).text(orderPartialPrice + " zł");
}

function changeOrderTotalPrice(){
    orderTotalPrice = orderPartialPrice;
    
    if(deliveryMethod != 0){
        orderTotalPrice += parseFloat(deliveryPrices[deliveryMethod - 1]);
        orderTotalPrice = Number(orderTotalPrice.toFixed(2));
    }

    $(".totalPrice").find("p").eq(1).text(orderTotalPrice + " zł");
}

function submitUserDataForm(event){
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
    let discountcode = $("#discountCode").val();

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
            else if(pass !== confirmpass){
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
                discountcode:discountcode,
                items:items
            }, 
            function(data){
                const returnedData = JSON.parse(data);

                if(returnedData.isValidationSucceed){
                    showOrderInfo(returnedData.orderNumber);
                }
                else{
                    showErrorMessage(returnedData.msg);
                }
            })      
    }       
}

function submitUserLoginForm(event){
    event.preventDefault();

    let login = $("#userlogin").val();
    let pass = $("#userpass").val();

    $.post("login.php", 
    {
        login:login,
        pass:pass,                   
    }, 
    function(data){

        const returnedData = JSON.parse(data);

        if(returnedData.isValidationSucceed){
            showSuccessMessage(returnedData.msg);

            $("#newAccountCheckBox").prop("checked", false);

            $('.loginBtn, .userSignin, .newAccountCheckBox').css('display', 'none');
            $('#login, #pass, #confirmPass').css('display', 'none');

            $("#firstName").val(returnedData.first_name);
            $("#lastName").val(returnedData.last_name);
            $("#country").val(returnedData.country);
            $("#address").val(returnedData.street);
            $("#postCode").val(returnedData.post_code);
            $("#city").val(returnedData.city);
            $("#telephone").val(returnedData.phone_number);

            userAddress.country = returnedData.country;
            userAddress.steet = returnedData.street;
            userAddress.postCode = returnedData.post_code;
            userAddress.city = returnedData.city;

            closeLoginContainer();
        }
        else{
            showErrorMessage(returnedData.msg);
        }
    })  
}

function stopPropagationBackground(event){
    event.stopPropagation();
}

function showErrorMessage(msg){
    $('.popUpMessage').text(msg);

    $('.popUpInfo')
        .css("display", "flex")
        .css("background-color", "rgb(227,67,67)")
        .hide()
        .fadeIn('normal')
        .delay(2500)
        .fadeOut();
}

function showSuccessMessage(msg){
    $('.popUpMessage').text(msg);

    $('.popUpInfo')
        .css("display", "flex")
        .css("background-color", "rgb(106,227,124)")
        .hide()
        .fadeIn('normal')
        .delay(2500)
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

function toggleAddressData(){
    if($('#differentAddressCheckBox')[0].checked){

        $("#country").val("poland");
        $("#address").val("");
        $("#postCode").val("");
        $("#city").val("");
    }
    else{
        $("#country").val(userAddress.country);
        $("#address").val(userAddress.steet);
        $("#postCode").val(userAddress.postCode);
        $("#city").val(userAddress.city);
    }   
}

function matchPaymentMethods(callerMethod){

    let paymentMethod1 = $(".paymentMethod1").first();
    let paymentMethod2 = $(".paymentMethod2").first();
    let paymentMethod3 = $(".paymentMethod3").first();

    switch(callerMethod){
        case 1:
            paymentMethod1.css("display", "flex");
            paymentMethod2.css("display", "none");
            paymentMethod3.css("display", "flex");

            deliveryMethod = 1;
            break;
        case 2:
            paymentMethod1.css("display", "none");
            paymentMethod2.css("display", "flex");
            paymentMethod3.css("display", "none");

            deliveryMethod = 2;
            break;
        case 3:
            paymentMethod1.css("display", "flex");
            paymentMethod2.css("display", "flex");
            paymentMethod3.css("display", "none");

            deliveryMethod = 3;
            break;
    }

    changeOrderTotalPrice();
}

function openLoginContainer(){
    $('.loginBackgroundContainer')
        .css("display", "flex")
        .hide()
        .fadeIn('normal');
}

function closeLoginContainer(){
    $('.loginBackgroundContainer')
        .fadeOut('normal');
}