# GarantiPos
Garanti Bankası sanal pos entegrasyonu için kullanımı kolay PHP sınıfı.<br>
Kartlı ödeme ve GarantiPay ile ödeme için kullanılabilir.

## Örnek pos konfigürasyonu
```php
<?php 
// Pos tanımları, sipariş bilgileri ve ödeme bilgileri burada tanımlanıyor
$params = array(
    // Pos tanımları (Pos panelinde tanımlanıp buraya girilecek)
    'mode' => "TEST", // Pos modu, test için: "TEST", production için: "PROD"
    'merchantID' => "XXXXX", // Merchant numarası
    'terminalID' => "XXXXX", // Terminal numarası
    'provUserID' => "PROVAUT", // Provision kullanıcı adı
    'provUserPassword' => "XXXXX", // Provision kullanıcı parolası
    'garantiPayProvUserID' => "PROVOOS", // GarantiPay için provision kullanıcı adı
    'garantiPayProvUserPassword' => "XXXXX", // GarantiPay için provision kullanıcı parolası
    'storeKey' => "XXXXX", // 24byte hex 3D secure anahtarı
    'successUrl' => "https://localhost/garantipos/example.php?action=success", // Başarılı ödeme sonrası dönülecek adres
    'errorUrl' => "https://localhost/garantipos/example.php?action=error", // Hatalı ödeme sonrası dönülecek adres
    'companyName' => "GarantiPos PHP", // Firma adı
    'paymentType' => "creditcard", // Ödeme tipi - kredi kartı için: "creditcard", GarantiPay için: "garantipay"

    // Müşteri tanımları
    'orderNo' => uniqid(), // Sipariş numarası
    'amount' => 1234, // Çekilecek tutar (ondalıklı olarak değil tam sayı olarak gönderilmeli, örn. 12.34tl için 1234 gönderilmeli)
    'installmentCount' => "", // Tek çekim olacaksa boş bırakılmalıdır
    'currencyCode' => 949, // Döviz cinsi kodu(varsayılan:949): TRY=949, USD=840, EUR=978, GBP=826, JPY=392
    'customerIP' => "127.0.0.1", // Müşteri IP adresi
    'customerEmail' => "x@x.com", // Müşteri e-mail adresi

    // Kart bilgisi tanımları (GarantiPay ile ödemede bu alanların doldurulması zorunlu değildir)
    'cardName' => "XXX XXX", // Kart üzerindeki ad soyad
    'cardNumber' => "XXXXXXXXXXXXXXXX", // Kart numarası (16 haneli boşluksuz)
    'cardExpiredMonth' => "XX", // Kart geçerlilik tarihi ay
    'cardExpiredYear' => "XX", // Kart geçerlilik tarihi yıl (yılın son 2 hanesi)
    'cardCvv' => "XXX", // Kartın arka yüzündeki son 3 numara(CVV kodu)
);
?>
```

## Örnek kullanım
Yukarıdaki konfigürasyon yapıldıktan sonra
```php
<?php
// GarantiPos sınıfı tanımlanıyor
$garantipos = new GarantiPos();
$garantipos->debugMode = false;
$params['paymentType'] = isset($_POST['paymenttype']) ? $_POST['paymenttype'] : $params['paymentType'];
$garantipos->setParams($params);

$action = isset($_GET['action']) ? $_GET['action'] : false;
if ($action) {
    $result = $garantipos->callback($action);
    if ($result['success'] == 'success') {
        unset($_SESSION['orderNumber']); // Sipariş başarıyla tamamlandığı için session siliniyor
    }

    var_dump($result);
} else {
    $garantipos->debugUrlUse = false; // Parametre değerlerinin check edildiği adrese gönderilmesi

    $garantipos->pay(); // 3D doğrulama için bankaya yönlendiriliyor
}
?>
```