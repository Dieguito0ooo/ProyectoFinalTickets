document.getElementById("loginForm").addEventListener("submit", async (e) => {
  e.preventDefault();

  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value.trim();

  try {
    const res = await fetch("http://localhost:8001/usuarios/login", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ email, password })
    });

    const data = await res.json();

    if (!res.ok) {
      alert(data.error || "Login fallido");
      return;
    }

    // ✅ Guardar token y usuario correctamente
    localStorage.setItem("token", data.token);
    localStorage.setItem("user", JSON.stringify(data.user));

    alert("Login exitoso ✅");
    window.location.href = "dashboard.html";

  } catch (err) {
    console.error("Error:", err);
    alert("Error de conexión. Revisa que el backend esté corriendo.");
  }
});
