<!DOCTYPE html>
<html lang="es">
<head><meta charset="utf-8"><style>
    body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
    .card { background: #fff; max-width: 520px; margin: 0 auto; border-radius: 8px; overflow: hidden; }
    .header { background: #E8710A; padding: 24px 28px; }
    .header h1 { color: #fff; margin: 0; font-size: 20px; }
    .body { padding: 28px; }
    .row { margin-bottom: 16px; }
    .label { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #888; margin-bottom: 4px; }
    .value { font-size: 15px; color: #111; }
    .comments { background: #f9f9f9; border-left: 3px solid #E8710A; padding: 12px 16px; border-radius: 4px; font-size: 14px; color: #333; line-height: 1.6; }
    .footer { padding: 16px 28px; border-top: 1px solid #eee; font-size: 12px; color: #aaa; }
</style></head>
<body>
<div class="card">
    <div class="header">
        <h1>Nuevo mensaje de contacto</h1>
    </div>
    <div class="body">
        <div class="row">
            <div class="label">Nombre</div>
            <div class="value">{{ $nombre }}</div>
        </div>
        <div class="row">
            <div class="label">Teléfono</div>
            <div class="value">{{ $telefono }}</div>
        </div>
        <div class="row">
            <div class="label">Correo</div>
            <div class="value"><a href="mailto:{{ $correo }}" style="color:#E8710A">{{ $correo }}</a></div>
        </div>
        <div class="row">
            <div class="label">Comentarios</div>
            <div class="comments">{{ $comentarios }}</div>
        </div>
    </div>
    <div class="footer">AutosMotosYMás — {{ now()->format('d/m/Y H:i') }}</div>
</div>
</body>
</html>
