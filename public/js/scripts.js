// CEP fetching
function fetchAddress() {
  const zipInput = document.getElementById('zip_code');
  const zipCode = zipInput.value.replace(/\D/g, '');

  document.getElementById('state').value = '';
  document.getElementById('city').value = '';
  document.getElementById('neighborhood').value = '';
  document.getElementById('street').value = '';
  document.getElementById('number').value = '';
  document.getElementById('complement').value = '';

  if (zipCode.length !== 8) {
    alert('CEP inválido. Digite 8 números.');
    return;
  }

  fetch(`https://viacep.com.br/ws/${zipCode}/json/`)
    .then(response => response.json())
    .then(data => {
      if (data.erro) {
        alert('CEP não encontrado.');
        return;
      }

      document.getElementById('state').value = data.uf;
      document.getElementById('city').value = data.localidade;
      document.getElementById('neighborhood').value = data.bairro;
      document.getElementById('street').value = data.logradouro;
    })
    .catch(() => {
      alert('Erro ao buscar o CEP.');
    });
}

// Coupon application
document.getElementById('zip_code')?.addEventListener('change', fetchAddress);

const applyCouponButton = document.getElementById('apply_coupon');

if (applyCouponButton) {
  applyCouponButton.addEventListener('click', async function (event) {
    event.preventDefault();

    const couponCode = document.getElementById('coupon').value;
    const subTotal = document.getElementById('subtotal').value;

    if (!couponCode) {
      alert('Por favor, insira um código de cupom.');
      return;
    }

    try {
      const response = await fetch(`/coupons/apply?code=${couponCode}&subtotal=${subTotal}`);

      console.log(response);

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || 'Erro ao aplicar o cupom.');
      }

      const data = await response.json();

      const subtotalElement = document.getElementById('subtotal');
      const shippingElement = document.getElementById('shipping');
      const discountElement = document.getElementById('discount');
      const totalElement = document.getElementById('show_total');

      let newTotal = parseFloat(subtotalElement.value) - data.discount;
      discountElement.textContent = data.discount.toFixed(2).replace('.', ',');
      newTotal += parseFloat(shippingElement.value);
      totalElement.textContent = newTotal.toFixed(2).replace('.', ',');

      const total = document.getElementById('total');
      total.value = newTotal;
    } catch (error) {
      alert(error.message);
    }
  });
}

