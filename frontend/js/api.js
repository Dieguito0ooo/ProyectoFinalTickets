const API_USUARIOS = "http://localhost:8001/usuarios";

async function login(email, password) {
    try {
        const res = await fetch(`${API_USUARIOS}/login`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ email, password })
        });

        if (!res.ok) {
            const err = await res.json();
            alert("Login falló: " + err.error);
            return null;
        }

        const data = await res.json();
        localStorage.setItem("token", data.token);
        return data;
    } catch (e) {
        console.error(e);
        alert("No se pudo conectar con la API de usuarios");
        return null;
    }
}
