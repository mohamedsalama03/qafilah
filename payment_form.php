<?php
include "config.php";
include "generate_hash.php";

// تحضير البيانات
$amount     = "150.00";
$reference  = "TXN-" . time();
$dateTime   = date('YmdHi');

// تحويل المبلغ إلى أصغر وحدة (مثلاً 150.00 × 100 = 15000)
$amountMinor = intval($amount * 100);

// توليد النص الخام للتشفير
$rawData = "Amount={$amount}&DateTimeLocalTrxn={$dateTime}&MerchantId=" . MERCHANT_ID .
           "&MerchantReference={$reference}&TerminalId=" . TERMINAL_ID;

// توليد SecureHash
$secureHash = generateSecureHash($rawData, SECRET_KEY);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>دفع عبر معاملات</title>
  <script src="https://npg.moamalat.net:6006/js/lightbox.js"></script>
</head>
<body>

  <button onclick="Lightbox.Checkout.showLightbox()">ادفع الآن</button>

  <script>
    Lightbox.Checkout.configure = {
      MID: "<?php echo MERCHANT_ID; ?>",
      TID: "<?php echo TERMINAL_ID; ?>",
      AmountTrxn: "<?php echo $amountMinor; ?>",
      MerchantReference: "<?php echo $reference; ?>",
      TrxDateTime: "<?php echo $dateTime; ?>",
      SecureHash: "<?php echo $secureHash; ?>",
      completeCallback: function (data) {
        alert("تم الدفع بنجاح ✅");
        console.log(data);
        // يمكنك هنا إرسال البيانات إلى السيرفر لتأكيد الطلب
      },
      errorCallback: function (error) {
        alert("حدث خطأ ❌");
        console.error(error);
      },
      cancelCallback: function () {
        alert("تم إلغاء الدفع ❎");
      }
    };
  </script>

</body>
</html>