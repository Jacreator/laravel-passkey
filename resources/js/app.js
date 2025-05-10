import "./bootstrap";
import axios from "axios";
import Alpine from "alpinejs";
import { startRegistration } from "@simplewebauthn/browser";


window.Alpine = Alpine;

document.addEventListener("alpine:init", () => {
  Alpine.data("registerPasskey", () => ({
      async register(form) {
          const options = await axios.get("/api/passkeys/register");
          const passkey = await startRegistration(options.data);

          form.addEventListener("formdata", ({ formData }) => {
              formData.set("passkey", JSON.stringify(passkey));
          });
          form.submit();
      },
  }));
});

Alpine.start();
