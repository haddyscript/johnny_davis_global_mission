{{-- ============================================================
     MINISTERIAL FELLOWSHIP — CONTACT US MODAL
============================================================ --}}
<div class="mfc-overlay" id="mfContactModal" role="dialog" aria-modal="true" aria-labelledby="mfContactTitle">
  <div class="mfc-backdrop"></div>
  <div class="mfc-box">
    <button type="button" class="mfc-close" id="mfContactClose" aria-label="Close contact form">&times;</button>

    <div class="mfc-body">
      <h2 class="mfc-title" id="mfContactTitle">Contact Us</h2>
      <p class="mfc-subtitle">Send us a message and we'll get back to you within 48 hours.</p>

      <form id="mfContactForm" method="POST" action="{{ route('contact.store') }}" novalidate>
        @csrf

        <div class="mfc-form-errors" id="mfFormErrors" hidden>
          <strong>Please fix the following:</strong>
          <ul id="mfFormErrorsList"></ul>
        </div>

        <div class="mfc-row">
          <div class="mfc-group">
            <label class="mfc-label" for="mfFirstName">First Name</label>
            <input class="mfc-input" id="mfFirstName" name="firstName" type="text" placeholder="John" required/>
          </div>
          <div class="mfc-group">
            <label class="mfc-label" for="mfLastName">Last Name</label>
            <input class="mfc-input" id="mfLastName" name="lastName" type="text" placeholder="Doe" required/>
          </div>
        </div>

        <div class="mfc-group">
          <label class="mfc-label" for="mfEmail">Email Address</label>
          <input class="mfc-input" id="mfEmail" name="email" type="email" placeholder="john@example.com" required/>
        </div>

        <div class="mfc-group">
          <label class="mfc-label" for="mfSubject">Subject</label>
          <select class="mfc-select" id="mfSubject" name="subject">
            <option value="">Select a subject...</option>
            <option value="general">General Inquiry</option>
            <option value="donation">Donation Question</option>
            <option value="volunteer">Volunteer Opportunities</option>
            <option value="partnership">Church Partnership</option>
            <option value="disaster">Disaster Relief Coordination</option>
            <option value="other">Other</option>
          </select>
        </div>

        <div class="mfc-group">
          <label class="mfc-label" for="mfMessage">Message</label>
          <textarea class="mfc-textarea" id="mfMessage" name="message" placeholder="Tell us how we can help..." maxlength="1000" required></textarea>
          <div class="mfc-char-counter" id="mfCharCounter">0 / 1000</div>
        </div>

        <button type="submit" class="mfc-submit" id="mfSubmitBtn">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
          Send Message
        </button>
      </form>

      <div class="mfc-success" id="mfFormSuccess" hidden>
        <div class="mfc-success-icon" aria-hidden="true">✅</div>
        <h3 class="mfc-success-title">Message Sent!</h3>
        <p class="mfc-success-text">Thank you for reaching out. We'll respond within 48 hours.</p>
      </div>
    </div>
  </div>
</div>

<script>
(function () {
  var openBtn   = document.getElementById('mfContactBtn');
  var modal     = document.getElementById('mfContactModal');
  var closeBtn  = document.getElementById('mfContactClose');
  var backdrop  = modal ? modal.querySelector('.mfc-backdrop') : null;
  var form      = document.getElementById('mfContactForm');
  var submitBtn = document.getElementById('mfSubmitBtn');
  var success   = document.getElementById('mfFormSuccess');
  var errorsBox = document.getElementById('mfFormErrors');
  var errorsList= document.getElementById('mfFormErrorsList');
  var charCounter = document.getElementById('mfCharCounter');
  var messageEl = document.getElementById('mfMessage');

  if (!openBtn || !modal || !form) return;

  function openModal() {
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
    closeBtn.focus();
  }
  function closeModal() {
    modal.classList.remove('open');
    document.body.style.overflow = '';
  }

  openBtn.addEventListener('click', function (e) {
    e.preventDefault();
    openModal();
  });
  closeBtn.addEventListener('click', closeModal);
  backdrop.addEventListener('click', closeModal);
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && modal.classList.contains('open')) closeModal();
  });

  messageEl.addEventListener('input', function () {
    var count = messageEl.value.length;
    charCounter.textContent = count + ' / 1000';
    charCounter.style.color = count > 900 ? '#ef4444' : count > 800 ? '#f59e0b' : '';
  });

  function clearFieldErrors() {
    ['mfFirstName', 'mfLastName', 'mfEmail', 'mfMessage'].forEach(function (id) {
      var field = document.getElementById(id);
      if (field) field.classList.remove('mfc-input-error');
    });
    errorsBox.hidden = true;
    errorsList.innerHTML = '';
  }

  function showFieldErrors(errors) {
    var idMap = { firstName: 'mfFirstName', lastName: 'mfLastName', email: 'mfEmail', message: 'mfMessage', subject: 'mfSubject' };
    errorsList.innerHTML = '';
    Object.keys(errors).forEach(function (key) {
      var field = document.getElementById(idMap[key] || key);
      if (field) field.classList.add('mfc-input-error');
      (errors[key] || []).forEach(function (msg) {
        var li = document.createElement('li');
        li.textContent = msg;
        errorsList.appendChild(li);
      });
    });
    errorsBox.hidden = false;
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    clearFieldErrors();

    submitBtn.disabled = true;
    var originalLabel = submitBtn.innerHTML;
    submitBtn.textContent = 'Sending...';

    var formData = new FormData(form);

    fetch(form.action, {
      method: 'POST',
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      body: formData,
    }).then(function (response) {
      if (response.ok) {
        form.hidden = true;
        success.hidden = false;
        form.reset();
        if (charCounter) charCounter.textContent = '0 / 1000';
        return;
      }
      if (response.status === 422) {
        return response.json().then(function (data) {
          showFieldErrors(data.errors || {});
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalLabel;
        });
      }
      throw new Error('Server error: ' + response.status);
    }).catch(function () {
      submitBtn.textContent = 'Something went wrong — try again';
      setTimeout(function () {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalLabel;
      }, 2500);
    });
  });
}());
</script>
