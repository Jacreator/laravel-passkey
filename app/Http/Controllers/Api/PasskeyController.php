<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use PHPUnit\Event\Runtime\PHP;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialUserEntity;
use Webauthn\AuthenticatorSelectionCriteria;
use Webauthn\PublicKeyCredentialCreationOptions;

class PasskeyController extends Controller
{
  public function registerOptions(Request $request)
  {
    $options = new PublicKeyCredentialCreationOptions(
      rp: new PublicKeyCredentialRpEntity(
        name: config('app.name'),
        id: parse_url(config('app.url'), PHP_URL_HOST),
      ),
      user: new PublicKeyCredentialUserEntity(
        name: $request->user()->email,
        id: $request->user()->id,
        displayName: $request->user()->name,
      ),
      challenge: Str::random(),
      authenticatorSelection: new AuthenticatorSelectionCriteria(
        authenticatorAttachment: AuthenticatorSelectionCriteria::AUTHENTICATOR_ATTACHMENT_NO_PREFERENCE,
        requireResidentKey: true,
      ),
    );

    Session::flash('passkey-registration-options', $options);

    return $options;
  }
}
