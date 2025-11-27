document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("ticketForm");
  const btnVolver = document.getElementById("btnVolver");

  btnVolver.addEventListener("click", () => {
    window.location.href = "dashboard.html";
  });

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const titulo = document.getElementById("titulo").value.trim();
    const descripcion = document.getElementById("descripcion").value.trim();
    const estado = document.getElementById("estado").value;
    const gestor_id = document.getElementById("gestor").value;

    const token = localStorage.getItem("token");

    if (!token) {
      alert("Debes iniciar sesión primero ❌");
      window.location.href = "index.html";
      return;
    }

    try {
      const res = await fetch("http://127.0.0.1:8002/tickets/crear", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer " + token
        },
        body: JSON.stringify({ titulo, descripcion, estado, gestor_id })
      });

      const data = await res.json();

      if (!res.ok) {
        alert(data.error || "No se pudo crear el ticket ❌");
        return;
      }

      alert("Ticket creado correctamente ✅\nID: " + data.ticket_id);
      form.reset();

    } catch (err) {
      console.error(err);
      alert("Error de conexión con el backend ❗");
    }
  });
});
