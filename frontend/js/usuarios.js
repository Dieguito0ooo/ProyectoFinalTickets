const token = localStorage.getItem("token");
const role = localStorage.getItem("role");

if (!token || role !== "admin") {
    window.location.href = "index.html";
}

const API = "http://127.0.0.1:8001/usuarios";


function volverDashboard() {
    window.location.href = "dashboard_admin.html";
}



let usuarios = [];

async function cargarUsuarios() {
    try {
        const res = await fetch(`${API}/all`, {
            headers: { "Authorization": "Bearer " + token }
        });

        if (!res.ok) {
            console.error("Error al cargar usuarios:", res.status);
            return;
        }

        usuarios = await res.json();
        mostrarTabla(usuarios);
    } catch (error) {
        console.error("Error de red:", error);
    }
}
function mostrarTabla(lista) {
    const tbody = document.getElementById("tablaUsuarios");
    tbody.innerHTML = "";
    if (lista.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6">No hay usuarios registrados</td></tr>`;
        return;
    }
    lista.forEach(u => {
        tbody.innerHTML += `
            <tr>
                <td>${u.id}</td>
                <td>${u.name}</td>
                <td>${u.email}</td>
                <td>${u.role}</td>
                <td>${u.active ? "Activo" : "Inactivo"}</td>
                <td>
                    <button onclick="editarUsuario(${u.id})" class="btnEditar">Editar</button>
                    <button onclick="eliminarUsuario(${u.id})" class="btnDanger">Eliminar</button>
                </td>
            </tr>
        `;
    });
}

cargarUsuarios();



document.getElementById("searchUser").addEventListener("input", function () {
    const q = this.value.toLowerCase();

    const filtrados = usuarios.filter(u =>
        u.name.toLowerCase().includes(q) ||
        u.email.toLowerCase().includes(q) ||
        u.role.toLowerCase().includes(q)
    );

    mostrarTabla(filtrados);
});



const modal = document.getElementById("modalCrear");

document.getElementById("btnNuevoUsuario").addEventListener("click", () => {
    // Limpiar campos
    document.getElementById("newNombre").value = "";
    document.getElementById("newEmail").value = "";
    document.getElementById("newPassword").value = "";
    document.getElementById("newRol").value = "gestor";
    document.getElementById("msgCrear").textContent = "";
    modal.style.display = "flex";
});

function cerrarModal() {
    modal.style.display = "none";
}



document.getElementById("btnGuardarNuevo").addEventListener("click", async () => {
    const nombre = document.getElementById("newNombre").value.trim();
    const email = document.getElementById("newEmail").value.trim();
    const password = document.getElementById("newPassword").value.trim();
    const rol = document.getElementById("newRol").value;
    const msg = document.getElementById("msgCrear");

    if (!nombre || !email || !password) {
        msg.textContent = "Todos los campos son obligatorios";
        msg.style.color = "red";
        return;
    }
    if (password.length < 6) {
        msg.textContent = "La contraseña debe tener al menos 6 caracteres";
        msg.style.color = "red";
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        msg.textContent = "Email inválido";
        msg.style.color = "red";
        return;
    }

    const res = await fetch(`${API}/register`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + token
        },
        body: JSON.stringify({
            name: nombre,
            email,
            password,
            role: rol
        })
    });

    if (!res.ok) {
        const err = await res.json();
        msg.textContent = err.error || "Error al crear usuario";
        msg.style.color = "red";
        return;
    }

    msg.textContent = "Usuario creado exitosamente";
    msg.style.color = "green";

    setTimeout(() => {
        cerrarModal();
        cargarUsuarios();
    }, 800);
});



const modalEditar = document.getElementById("modalEditar");

function abrirModalEditar(id) {
    const usuario = usuarios.find(u => u.id === id);
    
    if (!usuario) {
        alert("Usuario no encontrado");
        return;
    }

    document.getElementById("editId").value = usuario.id;
    document.getElementById("editNombre").value = usuario.name;
    document.getElementById("editEmail").value = usuario.email;
    document.getElementById("editPassword").value = "";
    document.getElementById("editRol").value = usuario.role;
    document.getElementById("msgEditar").textContent = "";

    modalEditar.style.display = "flex";
}

function cerrarModalEditar() {
    modalEditar.style.display = "none";
}


window.onclick = function(event) {
    if (event.target == modalCrear) cerrarModal();
    if (event.target == modalEditar) cerrarModalEditar();
}



async function eliminarUsuario(id) {
    if (!confirm("¿Seguro que quieres eliminar este usuario?")) return;

    await fetch(`${API}/${id}`, {
        method: "DELETE",
        headers: { "Authorization": "Bearer " + token }
    });

    cargarUsuarios();
}