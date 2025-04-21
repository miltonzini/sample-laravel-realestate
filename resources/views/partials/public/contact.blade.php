<section class="left-background-image-section text-dark-b" id="contact">
    <div class="block-1">
        &nbsp;
    </div>
    <div class="block-2">
        <div class="content-wrapper">
            <h3 class="section-title-center text-strong">Hablá con <span class="text-primary">nosotros</span></h3>
            <form method="post" action="{{ route('contact.submit') }}" onsubmit="showSendingMessageToast()">
                @csrf
                <input type="text" name="website" id="form-hidden-field">
                <input type="text" name="full-name" id="form-full-name" required placeholder="Nombre y Apellido" value="{{ old('full-name') }}">
                <input type="tel" name="telephone" id="form-tel" placeholder="Teléfono" value="{{ old('telephone') }}">
                <input type="email" name="email" id="form-email" required placeholder="Email" value="{{ old('email') }}">
                <textarea name="message" id="form-message" minlength="20" maxlength="2000" required placeholder="Mensaje.">{{ old('message') }}</textarea>
                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                <input type="submit" value="Contactanos" class="btn btn-accent">
            </form>
        </div>
    </div>
</section>
