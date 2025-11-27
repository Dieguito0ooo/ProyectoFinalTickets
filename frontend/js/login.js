document.getElementById("loginForm").addEventListener("submit", async (e) => {
  e.preventDefault();

  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;

  try {
    const res = await fetch("http://127.0.0.1:8001/usuarios/login", {
      method:"POST",
      headers:{ "Content-Type":"application/json" },
      body: JSON.stringify({ email, password })
    });

    const data = await res.json();

    if (!res.ok) {
      alert(data.error || "Login fallido");
      return;
    }

    // Guardar token
    localStorage.setItem("token", data.token);
    alert("Login exitoso ✅");
    console.log("Token guardado:", data.token);

  } catch(err) {
    console.error("Error:", err);
    alert("Error de conexión. Revisa que el backend esté corriendo.");
  }
});
