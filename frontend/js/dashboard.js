document.addEventListener("DOMContentLoaded", () => {
  // Obtener usuario guardado
  const rawUser = localStorage.getItem("user");
  if (!rawUser) {
    console.warn("⚠ No hay usuario guardado, redirigiendo al login...");
    window.location.href = "index.html";
    return;
  }

  const user = JSON.parse(rawUser);

  // Mostrar datos en la UI
  const userNameEl  = document.getElementById("userName");
  const userEmailEl = document.getElementById("userEmail");
  const userRoleEl  = document.getElementById("userRole");

  if (userNameEl)  userNameEl.textContent  = user.name;
  if (userEmailEl) userEmailEl.textContent = user.email;
  if (userRoleEl)  userRoleEl.textContent  = user.role;

  // Botones navegación
  const btnCrear  = document.getElementById("btnCrearTicket");
  const btnListar = document.getElementById("btnListarTickets");
  const btnLogout = document.getElementById("btnLogout");

  if (btnCrear) {
    btnCrear.addEventListener("click", () => {
      window.location.href = "crear_ticket.html";
    });
  }

  if (btnListar) {
    btnListar.addEventListener("click", () => {
      window.location.href = "listar_tickets.html";
    });
  }

  // Cerrar sesión
  if (btnLogout) {
    btnLogout.addEventListener("click", () => {
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      alert("✅ Sesión cerrada");
      window.location.href = "index.html";
    });
  }
});
