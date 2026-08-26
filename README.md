# Audiomania Eventos - Demo Web

Proyecto de demostración para Audiomania Eventos: web con WordPress + WooCommerce para reservas de eventos (DJ, sonido, iluminación, photocall, hinchables, etc.).

## Estado del Proyecto

- **Estado:** Fase 2/4 - Diseño con imágenes remotas (WebP preparadas)
- **Última actualización:** 2026-08-26
- **URL Demo:** http://178.104.253.211/audiomaniaeventos/
- **URL Admin:** http://178.104.253.211/audiomaniaeventos/wp-admin
- **Servidor:** 178.104.253.211 (Debian, Nginx + PHP 8.4 + MariaDB)
- **Tema:** Hello Elementor
- **Constructor:** Elementor + GreenShift (animaciones)

## Infraestructura

| Componente | Detalle |
|------------|---------|
| CMS | WordPress 6.x |
| E-commerce | WooCommerce 11.0.1 |
| Constructor | Elementor 4.2.3 + GreenShift 13.1.7 |
| SEO | Rank Math 1.0.276 |
| Pagos | Stripe (modo test) |
| Cache | WP Super Cache 3.1.1 |
| WhatsApp | WP-WhatsApp-Chat 8.6.2 |
| Imágenes | Smush 4.3.2 |

## Estructura del Proyecto

```
audiomania-events-demo/
├── README.md                  # Este archivo
├── docs/
│   ├── auditoria-site.md      # Auditoría del sitio actual (audiomaniaeventos.com)
│   ├── diseño.md              # Paleta, tipografías, decisiones de diseño
│   ├── imagenes.md            # Inventario de imágenes del sitio actual (URLs remotas)
│   ├── producto-catalogo.md   # Catálogo de productos/servicios
│   ├── paginas.md             # Estructura de páginas
│   └── cronograma.md          # Fases del proyecto
├── assets/                    # Assets locales (logos, fuentes, etc.)
└── .gitignore
```

## Productos / Servicios

| Producto | Precio |
|----------|--------|
| DJ para Eventos | Personalizado |
| Alquiler de Sonido | Personalizado |
| Iluminación LED y Efectos | Personalizado |
| Photocall | Personalizado |
| Hinchables LED | Personalizado |
| Espectáculos | Personalizado |
| Paquete Completo | Personalizado |

## Fases del Proyecto

1. ✅ **FASE 1:** Servidor + WordPress core + Nginx config
2. ✅ **FASE 2:** Plugins (WooCommerce, Elementor, GreenShift, Stripe, etc.)
3. ⏳ **FASE 3:** Diseño con Elementor + GreenShift + animaciones
4. ⏳ **FASE 4:** Contenido, imágenes y copys
5. ⏳ **FASE 5:** Testing, pulido y entrega

## Cambios Recientes (2026-08-26)

- ✅ Número WhatsApp corregido: `34669621139` (en fallback, footer y customizer)
- ✅ Número footer actualizado: `+34 669 621 139`
- ✅ Logo header reducido: 1.35rem → 1.05rem (base), 1.1rem → 0.85rem (scroll)
- ✅ Imágenes WebP subidas al servidor en `/uploads/2026/08/` (Alquiler-altavoz, Alquiler-Dj-boot, Alquiler-nexus-tenerife, Dj-Boda, Dj-tenerife, Mobiliario-eventos-alquiler, Organizacion-Eventos-Tenerife, paquete-completo, etc.)
- ⏳ Pendiente: Reemplazo de imágenes .jpg por .webp en Elementor
- ⏳ Pendiente: Configurar plugin WP-WhatsApp-Chat con número correcto para eliminar botón duplicado

## Notas

- **Sin dominio propio** (demo en IP/subdirectorio)
- **Sin SSL** (por ahora)
- **Idioma:** Español únicamente
- **Imágenes:** Se usan URLs remotas del sitio actual (no importación local) para agilizar el diseño
- **Moneda:** EUR
- **Zonas de entrega:** Tenerife + España peninsular
