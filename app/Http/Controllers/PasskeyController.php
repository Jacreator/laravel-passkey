<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Passkey;
use Illuminate\Http\Request;
use Webauthn\PublicKeyCredential;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\Denormalizer\WebauthnSerializerFactory;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\AttestationStatement\AttestationStatementSupportManager;

class PasskeyController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $data = $request->validateWithBag('createPasskey', [
      'name' => ['required', 'string', 'max:255'],
      'passkey' => ['required', 'json'],
    ]);

    /** @var PublicKeyCredential $publicKeyCredential */
    $publicKeyCredential = (new WebauthnSerializerFactory(AttestationStatementSupportManager::create()))
      ->create()
      ->deserialize($data['passkey'], PublicKeyCredential::class, 'json');

    if (! $publicKeyCredential->response instanceof AuthenticatorAttestationResponse) {
      return to_route('login');
    }

    try {
      $publicKeyCredentialSource = AuthenticatorAttestationResponseValidator::create()->check(
        authenticatorAttestationResponse: $publicKeyCredential->response,
        publicKeyCredentialCreationOptions: Session::get('passkey-registration-options'),
        request: $request->getHost(),
      );
    } catch (Throwable $error) {
      throw ValidationException::withMessages([
        'name' => 'The given passkey is invalid.'
      ])->errorBag('createPasskey');
    }

    $request->user()->passkeys()->create([
      'name' => $data['name'],
      'credential_id' => base64_encode($publicKeyCredentialSource->publicKeyCredentialId), // $publicKeyCredentialSource->publicKeyCredentialId,
      'data' => $publicKeyCredentialSource,
    ]);

    return to_route('profile.edit')->withFragment('managePasskeys');
  }

  /**
   * Display the specified resource.
   */
  public function show(Passkey $passkey)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Passkey $passkey)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Passkey $passkey)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Passkey $passkey)
  {
    Gate::authorize('delete', $passkey);

    $passkey->delete();

    return to_route('profile.edit')->withFragment('managePasskeys');
  }
}
