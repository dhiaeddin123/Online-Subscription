var subscriptionId="<?=$subscription->id?>";
var clientSecret="<?=$subscription->latest_invoice->payment_intent->client_secret?>";
alert(subscriptionId);
var success=document.getElementsByClassName("success");
const btn = document.querySelector('#submit-payment-btn');
btn.addEventListener('click', function (e) {
  e.preventDefault();
  const nameInput = document.getElementById('name');

  // Create payment method and confirm payment intent.
  stripe.confirmCardPayment(clientSecret, {
    payment_method: {
      card: cardElement,
      billing_details: {
        name: nameInput.value,
      },
    }
  }).then((result) => {
    if(result.error) {
      alert(result.error.message);
    } else {
        success.style.display = "block";
    }
  });
});
(function() {
    'use strict';
  
    var elements = stripe.elements({
      fonts: [
        {
          cssSrc: 'https://fonts.googleapis.com/css?family=Roboto',
        },
      ],
      // Stripe's examples are localized to specific languages, but if
      // you wish to have Elements automatically detect your user's locale,
      // use `locale: 'auto'` instead.
      locale: window.__exampleLocale
    });
  
    var card = elements.create('card', {
      iconStyle: 'solid',
      style: {
        base: {
          iconColor: '#c4f0ff',
          color: '#fff',
          fontWeight: 500,
          fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
          fontSize: '16px',
          fontSmoothing: 'antialiased',
  
          ':-webkit-autofill': {
            color: '#fce883',
          },
          '::placeholder': {
            color: '#87BBFD',
          },
        },
        invalid: {
          iconColor: '#FFC7EE',
          color: '#FFC7EE',
        },
      },
    });
    card.mount('#example1-card');
  
    registerElements([card], 'example1');
  })();