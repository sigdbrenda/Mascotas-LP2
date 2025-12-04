# modelos/usuario.py
from config.conexion import Conexion

class Usuario:
    def __init__(self, id=None, nombre="", correo="", telefono="", mascota=""):
        self.id = id
        self.nombre = nombre
        self.correo = correo
        self.telefono = telefono
        self.mascota = mascota

    def insertar(self):
        con = Conexion().conectar()
        cur = con.cursor()
        sql = "INSERT INTO usuarios(nombre, correo, telefono, mascota) VALUES (?, ?, ?, ?)"
        cur.execute(sql, (self.nombre, self.correo, self.telefono, self.mascota))
        con.commit()
        self.id = cur.lastrowid
        cur.close()
        con.close()
        return self.id

    def actualizar(self):
        if not self.id:
            raise ValueError("Usuario debe tener id para actualizar")
        con = Conexion().conectar()
        cur = con.cursor()
        sql = "UPDATE usuarios SET nombre=?, correo=?, telefono=?, mascota=? WHERE id=?"
        cur.execute(sql, (self.nombre, self.correo, self.telefono, self.mascota, self.id))
        con.commit()
        cur.close()
        con.close()

    @staticmethod
    def obtener_por_id(id_usuario):
        con = Conexion().conectar()
        cur = con.cursor()
        cur.execute("SELECT * FROM usuarios WHERE id=?", (id_usuario,))
        row = cur.fetchone()
        cur.close()
        con.close()
        return dict(row) if row else None

    @staticmethod
    def listar_todos():
        con = Conexion().conectar()
        cur = con.cursor()
        cur.execute("SELECT * FROM usuarios ORDER BY nombre")
        rows = cur.fetchall()
        cur.close()
        con.close()
        return [dict(r) for r in rows]
