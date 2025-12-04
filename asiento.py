# modelos/asiento.py
from config.conexion import Conexion
from datetime import datetime

class Asiento:
    def __init__(self, id=None, evento_id=None, fecha_creacion=None, estado="pendiente"):
        self.id = id
        self.evento_id = evento_id
        self.fecha_creacion = fecha_creacion or datetime.now().isoformat(timespec="seconds")
        self.estado = estado

    def insertar(self):
        con = Conexion().conectar()
        cur = con.cursor()
        cur.execute("INSERT INTO asientos(evento_id, fecha_creacion, estado) VALUES (?, ?, ?)",
                    (self.evento_id, self.fecha_creacion, self.estado))
        con.commit()
        self.id = cur.lastrowid
        cur.close()
        con.close()
        return self.id

    def marcar_como_enviado(self):
        if not self.id:
            raise ValueError("Asiento debe tener id para marcar")
        con = Conexion().conectar()
        cur = con.cursor()
        cur.execute("UPDATE asientos SET estado=? WHERE id=?", ("enviado", self.id))
        con.commit()
        cur.close()
        con.close()
        self.estado = "enviado"

    @staticmethod
    def listar_pendientes():
        con = Conexion().conectar()
        cur = con.cursor()
        cur.execute("SELECT a.*, e.titulo, e.usuario_id FROM asientos a JOIN eventos e ON a.evento_id=e.id WHERE a.estado='pendiente'")
        rows = cur.fetchall()
        cur.close()
        con.close()
        return [dict(r) for r in rows]
