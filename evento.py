# modelos/evento.py
from config.conexion import Conexion
from datetime import datetime, timedelta
from modelos.venta import Venta

class Evento:  # Evento = Recordatorio
    def __init__(self, id=None, usuario_id=None, titulo="", fecha_programada=None, descripcion="", estado="pendiente"):
        self.id = id
        self.usuario_id = usuario_id
        self.titulo = titulo
        self.fecha_programada = fecha_programada or datetime.now().isoformat(timespec="seconds")
        self.descripcion = descripcion
        self.estado = estado

    def insertar_recordatorio(self):
        con = Conexion().conectar()
        cur = con.cursor()
        sql = "INSERT INTO eventos(usuario_id, titulo, fecha_programada, descripcion, estado) VALUES (?, ?, ?, ?, ?)"
        cur.execute(sql, (self.usuario_id, self.titulo, self.fecha_programada, self.descripcion, self.estado))
        con.commit()
        self.id = cur.lastrowid
        cur.close()
        con.close()
        return self.id

    def actualizar_estado(self, nuevo_estado):
        if not self.id:
            raise ValueError("Evento debe tener id para actualizar estado")
        con = Conexion().conectar()
        cur = con.cursor()
        cur.execute("UPDATE eventos SET estado=? WHERE id=?", (nuevo_estado, self.id))
        con.commit()
        cur.close()
        con.close()
        self.estado = nuevo_estado

    @staticmethod
    def listar_todos():
        con = Conexion().conectar()
        cur = con.cursor()
        cur.execute("SELECT * FROM eventos ORDER BY fecha_programada DESC")
        rows = cur.fetchall()
        cur.close()
        con.close()
        return [dict(r) for r in rows]

    @staticmethod
    def generar_recordatorios_automaticos(dias_espera=30):
        """
        Revisa las últimas compras de cada usuario. Si ha pasado 'dias_espera' desde la última compra,
        crea un evento/recordatorio.
        """
        con = Conexion().conectar()
        cur = con.cursor()
        # obtener lista de usuarios
        cur.execute("SELECT id, nombre, correo FROM usuarios")
        usuarios = cur.fetchall()
        inserted = []
        for u in usuarios:
            uid = u["id"]
            ultima = Venta.obtener_ultima_compra(uid)
            if not ultima:
                continue
            fecha_ultima = datetime.fromisoformat(ultima["fecha"])
            if datetime.now() - fecha_ultima >= timedelta(days=dias_espera):
                # crear recordatorio
                titulo = f"Recordatorio: volver a comprar ({ultima['producto']})"
                descripcion = f"Última compra el {fecha_ultima.date()} producto: {ultima['producto']}"
                fecha_prog = (fecha_ultima + timedelta(days=dias_espera)).isoformat(timespec="seconds")
                cur.execute(
                    "INSERT INTO eventos(usuario_id, titulo, fecha_programada, descripcion, estado) VALUES (?, ?, ?, ?, ?)",
                    (uid, titulo, fecha_prog, descripcion, "pendiente")
                )
                inserted.append(cur.lastrowid)
        con.commit()
        cur.close()
        con.close()
        return inserted  # lista de ids insertados
