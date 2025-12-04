# modelos/venta.py
from config.conexion import Conexion
from datetime import datetime

class Venta:
    def __init__(self, id=None, usuario_id=None, fecha=None, producto="", cantidad=1, total=0.0):
        self.id = id
        self.usuario_id = usuario_id
        self.fecha = fecha or datetime.now().isoformat(timespec="seconds")
        self.producto = producto
        self.cantidad = cantidad
        self.total = total

    def insertar(self):
        con = Conexion().conectar()
        cur = con.cursor()
        sql = "INSERT INTO ventas(usuario_id, fecha, producto, cantidad, total) VALUES (?, ?, ?, ?, ?)"
        cur.execute(sql, (self.usuario_id, self.fecha, self.producto, self.cantidad, self.total))
        con.commit()
        self.id = cur.lastrowid
        cur.close()
        con.close()
        return self.id

    def eliminar(self):
        if not self.id:
            raise ValueError("Venta debe tener id para eliminar")
        con = Conexion().conectar()
        cur = con.cursor()
        cur.execute("DELETE FROM ventas WHERE id=?", (self.id,))
        con.commit()
        cur.close()
        con.close()

    @staticmethod
    def obtener_ultima_compra(usuario_id):
        con = Conexion().conectar()
        cur = con.cursor()
        cur.execute("SELECT * FROM ventas WHERE usuario_id=? ORDER BY fecha DESC LIMIT 1", (usuario_id,))
        row = cur.fetchone()
        cur.close()
        con.close()
        return dict(row) if row else None

    @staticmethod
    def listar_por_usuario(usuario_id):
        con = Conexion().conectar()
        cur = con.cursor()
        cur.execute("SELECT * FROM ventas WHERE usuario_id=? ORDER BY fecha DESC", (usuario_id,))
        rows = cur.fetchall()
        cur.close()
        con.close()
        return [dict(r) for r in rows]
