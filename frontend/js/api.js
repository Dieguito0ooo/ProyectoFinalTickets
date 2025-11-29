const API_USUARIOS = "http://127.0.0.1:8001";

async function apiLogin(email, password) {
  try {
    const res = await fetch(API_USUARIOS + "/usuarios/login", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ email, password })
    });

    const data = await res.json();
    return data;

  } catch (err) {
    console.error(err);
    return { error: "No se pudo conectar al servidor" };
  }
}

// Exponer
window.apiLogin = apiLogin;
