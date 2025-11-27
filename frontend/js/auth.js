// auth.js - maneja el formulario de login (index.html)
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("loginForm");
  const errorMsg = document.getElementById("errorMsg");
  if (!form) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    errorMsg.textContent = "";

    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();

    if (!email || !password) {
      errorMsg.textContent = "Email y contraseña son obligatorios.";
      return;
    }

    try {
      const data = await apiFetch(API.usuariosBase + "/login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password })
      });

      // La respuesta esperada tiene { token, user }
      if (!data || !data.token) {
        errorMsg.textContent = (data && data.error) ? data.error : "Error al iniciar sesión";
        return;
      }

      localStorage.setItem("token", data.token);
      localStorage.setItem("user", JSON.stringify(data.user || {}));

      // redirige al dashboard (lo crearemos después)
      window.location.href = "dashboard.html";

    } catch (err) {
      if (err && err.body && err.body.error) {
        errorMsg.textContent = err.body.error;
      } else if (err && err.status === 401) {
        errorMsg.textContent = "Credenciales inválidas";
      } else {
        errorMsg.textContent = "Error de conexión. Revisa que el backend esté corriendo.";
        console.error(err);
      }
    }
  });
});
