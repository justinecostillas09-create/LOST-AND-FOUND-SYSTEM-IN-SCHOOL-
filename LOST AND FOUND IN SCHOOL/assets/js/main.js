// Campus Lost & Found - Client-Side Enhancements

document.addEventListener('DOMContentLoaded', () => {
  // Auto-dismiss alerts after 5 seconds
  const alerts = document.querySelectorAll('.alert-dismissible');
  alerts.forEach(alert => {
    setTimeout(() => {
      const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
      if (bsAlert) bsAlert.close();
    }, 5000);
  });

  // Photo Upload Live Preview
  const photoInput = document.getElementById('photoInput');
  const photoPreview = document.getElementById('photoPreview');
  if (photoInput && photoPreview) {
    photoInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (event) => {
          photoPreview.src = event.target.result;
          photoPreview.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
      }
    });
  }

  // Quick Demo Account Auto-Fill on Login Page
  const demoRoleBtns = document.querySelectorAll('.btn-fill-demo');
  if (demoRoleBtns.length > 0) {
    const emailField = document.getElementById('email');
    const passField = document.getElementById('password');

    demoRoleBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const email = btn.getAttribute('data-email');
        const pass = btn.getAttribute('data-password');
        if (emailField && passField) {
          emailField.value = email;
          passField.value = pass;
          // highlight effect
          emailField.classList.add('is-valid');
          passField.classList.add('is-valid');
          setTimeout(() => {
            emailField.classList.remove('is-valid');
            passField.classList.remove('is-valid');
          }, 1000);
        }
      });
    });
  }
});

// Quick client-side filter helper for item list
function filterCardsRealtime() {
  const input = document.getElementById('clientSearchInput');
  if (!input) return;
  const filter = input.value.toLowerCase();
  const cards = document.querySelectorAll('.filterable-item-card');

  cards.forEach(card => {
    const text = card.textContent.toLowerCase();
    if (text.includes(filter)) {
      card.style.display = '';
    } else {
      card.style.display = 'none';
    }
  });
}
