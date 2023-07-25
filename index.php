<!DOCTYPE html>
<html lang="en-GB">
<head>
    <title>Smartbees zadanie.</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/cde750a9b3.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js_scripts.js?1"></script>
</head>
<body>
    <div class="mainContent">
        <div class="userDataContainer">     
            <div class="userDataLabel">
                <i class="fa-solid fa-user"></i>
                <p>1. TWOJE DANE</p>
            </div>

            <div onclick="openLoginContainer()">
                <button class="loginBtn" id="loginBtn">Logowanie</button>
            </div>

            <div class="userSignin">          
                <p><a href="#">Masz już konto? Kliknij żeby się zalogować.</a></p>
            </div> 

            <div class="newAccountCheckBox">
                <input type="checkbox" id="newAccountCheckBox" name="newAccountCheckBox" onclick="showHideLoginFields()">
                <p>Stwórz nowe konto</p>
            </div>   
            
            <form class="userDataForm" mehod="POST" action="process.php" id="userDataForm">
                <label for="login">Login:</label>
                <input type="text" id="login" name="login" placeholder="Login *">

                <label for="pass">Hasło:</label>
                <input type="password" id="pass" name="pass" placeholder="Hasło *">

                <label for="confirmPass">Powtórz hasło:</label>
                <input type="password" id="confirmPass" name="confirmPass" placeholder="Powtórz hasło *">

                <label for="firstName">Imię:</label>
                <input type="text" id="firstName" name="firstName" placeholder="Imię *">

                <label for="lastName">Nazwisko:</label>
                <input type="text" id="lastName" name="lastName" placeholder="Nazwisko *">

                <label for="country">Kraj: </label>
                <select name="country" id="country">
                    <option value="poland" selected>Polska</option>
                </select>

                <label for="address">Adres:</label>
                <input type="text" id="address" name="address" placeholder="Adres *">

                <div class="addressDetails">           
                    <label for="postCode">Kod pocztowy:</label>
                    <input type="text" id="postCode" class="postCode" name="postCode" placeholder="Kod pocztowy *"><br>

                    <label for="city">Miasto:</label>
                    <input type="text" id="city" name="city" placeholder="Miasto *">
                </div>
                
                <label for="telephone">Telefon:</label>
                <input type="tel" id="telephone" name="telephone" placeholder="Telefon *">
            </form>

            <div class="differentAddressCheckBox">
                <input type="checkbox" id="differentAddressCheckBox" name="differentAddressCheckBox" onclick="toggleAddressData()">
                <p>Dostawa pod inny adres</p>
            </div>                          
        </div>
        
        <div class="otherInfoContainer">
            <div class="deliveryAndPaymentMethodsWrapper">
                <div class="deliveryMethodContainer">
                    <div class="deliveryMethodLabel">
                        <i class="fa-solid fa-truck-fast"></i>
                        <p>2. METODA DOSTAWY</p>
                    </div>
                </div> 
                
                <div class="paymentMethodContainer">   
                    <div class="paymentMethodLabel">
                        <i class="fa-solid fa-credit-card"></i>
                        <p>3. METODA PŁATNOŚCI</p>
                    </div>

                    <div class="paymentMethod1">                        
                        <input type="radio" id="payU" name="paymentMethodRadio" value="1" form="userDataForm">
                        <img src="payment_method_images/method_1.png" alt="PayU" width="50" height="30">                           
                        <label for="payU">PayU</label>                      
                    </div>

                    <div class="paymentMethod2">                        
                        <input type="radio" id="onDelivery" name="paymentMethodRadio" value="2" form="userDataForm">
                        <img src="payment_method_images/method_2.png" alt="Płatność przy odbiorze" width="50" height="30">                           
                        <label for="onDelivery">Płatność przy odbiorze</label>                      
                    </div>

                    <div class="paymentMethod3">                        
                        <input type="radio" id="bankTransfer" name="paymentMethodRadio" value="3" form="userDataForm">
                        <img src="payment_method_images/method_3.png" alt="Przelew bankowy" width="50" height="30">                           
                        <label for="bankTransfer">Przelew bankowy - zwykły</label>                      
                    </div>

                    <div>
                        <button class="discountCodeBtn" onclick="toggleDiscountCodeContainer()">Dodaj kod rabatowy</button>
                    </div>

                    <div class="discountCodeContainer">
                        <label for="discountCode">Discount code:</label>
                        <input type="text" id="discountCode" name="discountCode" placeholder="Wprowadź kod">
                    </div>

                    <div>
                        <button class="activateDiscountCodeBtn" onclick="activateDiscountCode()">Aktywuj</button>
                    </div>
                </div>
            </div>
            
            <div class="summaryContainer">
                <div class="summaryLabel">
                    <i class="fa-solid fa-clipboard-list"></i>
                    <p>4. PODSUMOWANIE</p>
                </div>

                <div class="orderPriceContainer">
                    <div class="partialPrice">
                        <p>Suma częściowa</p>
                        <p>0,00 zł</p>
                    </div>

                    <div class="totalPrice">
                        <p>Łącznie</p>
                        <p>0,00 zł</p>
                    </div>
                </div>

                <label for="orderComment">Komentarz:</label>
                <textarea id="orderComment" name="orderComment" rows="4" cols="50" placeholder="Komentarz" form="userDataForm"></textarea>

                <div class="getNewsletterCheckBox">
                    <input type="checkbox" name="getNewsletterCheckBox" form="userDataForm">
                    <p>Zapisz się, aby otrzymywać newsletter</p>
                </div>  

                <div class="acceptRegulationsCheckBox">
                    <input type="checkbox" name="acceptRegulationsCheckBox" form="userDataForm">
                    <p>Zapoznałam/łem się z <a href="#">Regulaminem</a> zakupów</p>
                </div>  
            
                <div> 
                    <button class="confirmOrderBtn" type="submit" form="userDataForm">POTWIERDŹ ZAKUP</button>
                </div>       
            </div>
        <div>    
    </div>
    </div>
    </div>

    <div class="orderCompleteInfo">
        <p>Dziękujemy za zamówienie!</p>
        <p class="orderNumber">Twój numer zamówienia:</p>
    </div>

    <div class="popUpInfo">
        <p class="popUpMessage">Important message!</p>
    </div>

    <div class="loginBackgroundContainer" onclick="closeLoginContainer()">
        <div class="loginContainer" onclick="stopPropagationBackground(event)">                
                <form class="userLoginForm" id="userLoginForm" mehod="POST" action="login.php">
                    <label for="userlogin">Login:</label>
                    <input type="text" id="userlogin" name="userlogin" placeholder="Login *">

                    <label for="userpass">Hasło:</label>
                    <input type="password" id="userpass" name="userpass" placeholder="Hasło *">
                </form>   

                <div>
                    <button class="loginBtn" type="submit" form="userLoginForm">Logowanie</button>
                </div>                   
        </div>
    </div>
</body>
</html>