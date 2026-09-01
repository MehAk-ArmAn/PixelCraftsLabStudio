/* PixelCraftsLab — brand construction sequence.
   One artboard, one camera, one pixel that survives from the first frame
   to the centre of the final mark. */

const { useMemo } = React;

const INK = "#0C0D10";
const PAPER = "oklch(0.965 0.008 90)";
const MINT = "oklch(0.82 0.17 152)";
const MAGENTA = "oklch(0.72 0.19 320)";
const CHALK = "rgba(245,246,247,0.85)";

const BW = 3200, BH = 1800, CX = 1600, CY = 900;

const MOTION = {
  enter: (from, to, start, end) => animate({ from, to, start, end, ease: Easing.easeOutCubic }),
  draw: (from, to, start, end) => animate({ from, to, start, end, ease: Easing.easeInOutCubic }),
  pop: (from, to, start, end) => animate({ from, to, start, end, ease: Easing.easeOutBack }),
};

function kf(T, pts) {
  if (T <= pts[0][0]) return pts[0].slice(1);
  for (let i = 0; i < pts.length - 1; i++) {
    const a = pts[i], b = pts[i + 1];
    if (T <= b[0]) {
      const p = Easing.easeInOutCubic((T - a[0]) / (b[0] - a[0] || 1));
      return a.slice(1).map((v, k) => v + (b[k + 1] - v) * p);
    }
  }
  return pts[pts.length - 1].slice(1);
}

function mulberry(seed) {
  let a = seed >>> 0;
  return () => {
    a = (a + 0x6d2b79f5) >>> 0;
    let t = Math.imul(a ^ (a >>> 15), 1 | a);
    t = (t + Math.imul(t ^ (t >>> 7), 61 | t)) ^ t;
    return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
  };
}

/* ---------- the UI kit that gets crafted ---------- */
const KIT = [
  { id: "nav", x: 1340, y: 755, w: 520, h: 64, at: 0.7 },
  { id: "card", x: 1340, y: 845, w: 250, h: 200, at: 1.6 },
  { id: "input", x: 1610, y: 845, w: 250, h: 52, at: 2.5 },
  { id: "btn", x: 1610, y: 915, w: 170, h: 52, at: 3.3 },
  { id: "toggle", x: 1800, y: 915, w: 60, h: 34, at: 4.0 },
  { id: "chart", x: 1610, y: 987, w: 250, h: 58, at: 4.6 },
];

/* ---------- pixel material ---------- */
const MARK = [
  { dx: 0, dy: 0, c: MAGENTA },
  { dx: -38, dy: -38, c: MINT }, { dx: 38, dy: -38, c: MINT },
  { dx: -38, dy: 38, c: MINT }, { dx: 38, dy: 38, c: MINT },
  { dx: 0, dy: -38, c: "rgba(245,246,247,0.32)" }, { dx: -38, dy: 0, c: "rgba(245,246,247,0.32)" },
  { dx: 38, dy: 0, c: "rgba(245,246,247,0.32)" }, { dx: 0, dy: 38, c: "rgba(245,246,247,0.32)" },
];

const PIXELS = (() => {
  const r = mulberry(9137);
  const out = [];
  for (let i = 0; i < 9; i++) {
    out.push({
      role: "mark", i, c: MARK[i].c, size: 16,
      born: i === 0 ? 0 : 2.9 + r() * 3.4,
      fx: i === 0 ? CX : CX + (r() - 0.5) * 900,
      fy: i === 0 ? CY : CY + (r() - 0.5) * 620,
      tx: 1080 + (i % 3) * 40, ty: 1160 + Math.floor(i / 3) * 40,
      j: r() * 6.28, js: 0.5 + r(),
    });
  }
  for (let i = 0; i < 58; i++) {
    const col = i % 5, row = Math.floor(i / 5);
    out.push({
      role: "tray", i, size: 12 + Math.round(r() * 6),
      c: r() < 0.2 ? MINT : r() < 0.3 ? MAGENTA : "rgba(245,246,247,0.5)",
      born: 2.7 + r() * 4.4,
      fx: CX + (r() - 0.5) * 1500, fy: CY + (r() - 0.5) * 1000,
      tx: 1040 + col * 38, ty: 660 + row * 38,
      j: r() * 6.28, js: 0.4 + r() * 1.2,
    });
  }
  for (let i = 0; i < 30; i++) {
    const k = KIT[i % KIT.length];
    out.push({
      role: "feed", i, size: 10 + Math.round(r() * 8), k: i % KIT.length,
      c: r() < 0.35 ? MINT : r() < 0.5 ? MAGENTA : "rgba(245,246,247,0.6)",
      born: 2.9 + r() * 4.6,
      fx: CX + (r() - 0.5) * 1700, fy: CY + (r() - 0.5) * 1100,
      tx: k.x + k.w / 2 + (r() - 0.5) * k.w * 0.7, ty: k.y + k.h / 2 + (r() - 0.5) * k.h * 0.6,
      j: r() * 6.28, js: 0.5 + r() * 1.4,
    });
  }
  return out;
})();

/* ---------- small parts ---------- */
function Px({ x, y, size, color, opacity, rot, glow }) {
  return (
    <div style={{
      position: "absolute", left: x, top: y, width: size, height: size,
      marginLeft: -size / 2, marginTop: -size / 2, background: color, borderRadius: 1.5,
      opacity, transform: `rotate(${rot}deg)`,
      boxShadow: glow > 0.01 ? `0 0 ${18 * glow}px ${color}` : "none",
    }} />
  );
}

function Mono({ x, y, text, opacity, color, size = 20, align = "left" }) {
  return (
    <div style={{
      position: "absolute", left: x, top: y, opacity, color,
      fontFamily: "'Space Mono', monospace", fontSize: size, letterSpacing: "0.18em",
      textTransform: "uppercase", whiteSpace: "nowrap",
      transform: align === "center" ? "translateX(-50%)" : "none",
    }}>{text}</div>
  );
}

function Stroke({ d, p, color, width, opacity, cap = "round" }) {
  return (
    <path d={d} pathLength="1" fill="none" stroke={color} strokeWidth={width}
      strokeLinecap={cap} strokeLinejoin="round" strokeDasharray="1"
      strokeDashoffset={1 - p} opacity={opacity} />
  );
}

/* ---------- sections (all always mounted, keyed to cues) ---------- */

function Grid({ T, C }) {
  const o = MOTION.draw(0.5, 0.16, C.exp, C.craft)(T) * MOTION.draw(1, 0.35, C.build, C.launch + 1)(T);
  return (
    <div style={{
      position: "absolute", inset: 0, opacity: o,
      backgroundImage: "radial-gradient(circle, rgba(245,246,247,0.5) 1.4px, transparent 1.4px)",
      backgroundSize: "40px 40px", backgroundPosition: "0 0",
    }} />
  );
}

function Sheet({ T, C }) {
  const a = MOTION.enter(0, 1, C.craft - 0.5, C.craft + 0.9)(T) * MOTION.draw(1, 0, C.launch + 0.9, C.launch + 2.2)(T);
  const s = MOTION.draw(0.96, 1, C.craft - 0.5, C.craft + 1.1)(T) * MOTION.draw(1, 0.9, C.launch + 0.9, C.launch + 2.2)(T);
  if (a < 0.01) return null;
  return (
    <div style={{
      position: "absolute", left: CX - 1400, top: CY - 750, width: 2800, height: 1500,
      background: PAPER, borderRadius: 26, opacity: a,
      transform: `scale(${s})`, boxShadow: "0 80px 160px -60px rgba(0,0,0,0.8)",
    }} />
  );
}

function Guides({ T, C }) {
  const g = [
    { d: `M 1000 755 L 2200 755`, at: 0.5 }, { d: `M 1000 1045 L 2200 1045`, at: 0.85 },
    { d: `M 1340 520 L 1340 1300`, at: 1.2 }, { d: `M 1860 520 L 1860 1300`, at: 1.55 },
  ];
  const fade = MOTION.draw(1, 0, C.craft + 3.4, C.build - 0.4)(T);
  return (
    <svg viewBox={`0 0 ${BW} ${BH}`} width={BW} height={BH} style={{ position: "absolute", inset: 0, overflow: "visible" }}>
      {g.map((l, i) => (
        <g key={i}>
          <Stroke d={l.d} p={MOTION.draw(0, 1, C.exp + l.at, C.exp + l.at + 0.9)(T)}
            color={MAGENTA} width={2} opacity={0.55 * fade} cap="butt" />
        </g>
      ))}
      <g opacity={MOTION.draw(0.9, 0, C.craft - 0.7, C.craft + 0.5)(T)}>
        <Stroke d="M 1180 640 C 1300 560, 1560 570, 1650 640 C 1740 712, 1700 830, 1560 848 C 1410 866, 1150 810, 1180 640"
          p={MOTION.draw(0, 1, C.exp + 1.1, C.exp + 2.6)(T)} color={CHALK} width={3} opacity={0.5} />
        <Stroke d="M 1900 980 L 2210 980 L 2210 1120 L 1900 1120 Z"
          p={MOTION.draw(0, 1, C.exp + 2.0, C.exp + 3.2)(T)} color={CHALK} width={3} opacity={0.45} />
        <Stroke d="M 1240 1180 C 1360 1140, 1420 1240, 1560 1190"
          p={MOTION.draw(0, 1, C.exp + 2.9, C.exp + 3.9)(T)} color={MINT} width={4} opacity={0.7} />
      </g>
      <Stroke d="M 1120 1330 C 1420 1250, 1860 1400, 2140 1310"
        p={MOTION.draw(0, 1, C.exp + 3.4, C.exp + 4.8)(T)} color={MAGENTA}
        width={26} opacity={0.30 * MOTION.draw(1, 0, C.craft + 1.4, C.craft + 2.8)(T)} />
      <Stroke d="M 1160 560 C 1450 500, 1760 600, 2080 530"
        p={MOTION.draw(0, 1, C.exp + 4.0, C.exp + 5.3)(T)} color={MINT}
        width={18} opacity={0.26 * MOTION.draw(1, 0, C.craft + 1.4, C.craft + 2.8)(T)} />
    </svg>
  );
}

function PixelField({ T, C }) {
  const glow = MOTION.draw(1, 0, C.craft - 0.4, C.craft + 0.8)(T) + MOTION.draw(0, 1, C.launch + 1.2, C.launch + 2.4)(T);
  return PIXELS.map((p, idx) => {
    const born = C.exp - 3.5 + p.born;
    if (T < born - 0.05) return null;
    let a = MOTION.enter(0, 1, born, born + 0.45)(T);
    const snapS = C.craft + (p.role === "tray" ? 0.2 + (p.i % 12) * 0.09 : 0);
    let x, y, size = p.size, rot = 0;

    if (p.role === "feed") {
      const k = KIT[p.k];
      const fly = MOTION.pop(0, 1, C.craft + k.at - 0.9, C.craft + k.at + 0.05)(T);
      x = p.fx + (p.tx - p.fx) * fly;
      y = p.fy + (p.ty - p.fy) * fly;
      a *= MOTION.draw(1, 0, C.craft + k.at - 0.1, C.craft + k.at + 0.25)(T);
    } else if (p.role === "tray") {
      const snap = MOTION.pop(0, 1, snapS, snapS + 0.7)(T);
      x = p.fx + (p.tx - p.fx) * snap;
      y = p.fy + (p.ty - p.fy) * snap;
      a *= MOTION.draw(1, 0, C.build + 0.4 + (p.i % 9) * 0.05, C.build + 1.4 + (p.i % 9) * 0.05)(T);
    } else {
      const m = MARK[p.i];
      const snap = MOTION.pop(0, 1, snapS + 0.4, snapS + 1.2)(T);
      const home = MOTION.pop(0, 1, C.launch + 2.1 + p.i * 0.045, C.launch + 3.0 + p.i * 0.045)(T);
      const hx = CX + m.dx, hy = 810 + m.dy;
      const sx = p.fx + (p.tx - p.fx) * snap, sy = p.fy + (p.ty - p.fy) * snap;
      x = sx + (hx - sx) * home;
      y = sy + (hy - sy) * home;
      size = p.size + (28 - p.size) * home;
    }

    if (T < C.craft) {
      const w = Math.sin(T * p.js * 1.7 + p.j);
      const drift = MOTION.draw(1, 0, C.craft - 1.2, C.craft + 0.4)(T);
      x += w * 22 * drift;
      y += Math.cos(T * p.js * 1.3 + p.j) * 18 * drift;
      rot = w * 12 * drift;
    }
    if (a < 0.01) return null;
    return <Px key={idx} x={x} y={y} size={size} color={p.c} opacity={a} rot={rot} glow={glow} />;
  });
}

function Spark({ T, sc }) {
  return [0.12, 0.55].map((t0, i) => {
    const p = MOTION.draw(0, 1, t0, t0 + 1.1)(T);
    if (p <= 0.01 || p >= 0.999) return null;
    const r = (26 + p * 230) / sc;
    return <div key={i} style={{
      position: "absolute", left: CX, top: CY, width: r, height: r,
      marginLeft: -r / 2, marginTop: -r / 2, borderRadius: 999,
      border: `${1.5 / sc}px solid ${MINT}`, opacity: (1 - p) * 0.7,
    }} />;
  });
}

function CraftKit({ T, C }) {
  const gone = MOTION.draw(1, 0, C.build + 0.2, C.build + 1.0)(T);
  return KIT.map((k, i) => {
    const t0 = C.craft + k.at;
    const s = MOTION.pop(0.82, 1, t0, t0 + 0.55)(T);
    const a = MOTION.enter(0, 1, t0, t0 + 0.3)(T) * gone;
    if (a < 0.01) return null;
    const wob = MOTION.draw(1, 0, t0 + 0.4, C.build - 1.0)(T);
    const rot = ((i % 2 ? 1 : -1) * 0.8) * wob;
    const flash = MOTION.draw(1, 0, t0 + 0.1, t0 + 0.9)(T);
    const base = {
      position: "absolute", left: k.x, top: k.y, width: k.w, height: k.h,
      opacity: a, transform: `scale(${s}) rotate(${rot}deg)`, transformOrigin: "50% 50%",
      outline: flash > 0.02 ? `2px solid ${MINT}` : "none", outlineOffset: 6 * flash,
    };
    const ink = "rgba(20,21,26,0.92)";
    if (k.id === "nav") return (
      <div key={k.id} style={{ ...base, background: "#fff", borderRadius: 12, border: "1px solid rgba(20,21,26,0.14)", display: "flex", alignItems: "center", gap: 12, padding: "0 18px" }}>
        <div style={{ display: "grid", gridTemplateColumns: "repeat(3,7px)", gridTemplateRows: "repeat(3,7px)", gap: 2 }}>
          {MARK.map((m, j) => <div key={j} style={{ background: j === 0 ? MAGENTA : j < 5 ? MINT : "rgba(20,21,26,0.2)" }} />)}
        </div>
        <div style={{ width: 92, height: 9, background: ink, borderRadius: 5 }} />
        <div style={{ marginLeft: "auto", display: "flex", gap: 10 }}>
          {[54, 42, 62].map((w, j) => <div key={j} style={{ width: w, height: 7, background: "rgba(20,21,26,0.28)", borderRadius: 4 }} />)}
        </div>
      </div>
    );
    if (k.id === "card") {
      const paint = MOTION.draw(0, 1, t0 + 0.35, t0 + 1.25)(T);
      return (
        <div key={k.id} style={{ ...base, background: "#fff", borderRadius: 14, border: "1px solid rgba(20,21,26,0.14)", overflow: "hidden" }}>
          <div style={{ height: 118, background: "repeating-linear-gradient(135deg, rgba(20,21,26,0.08) 0 2px, transparent 2px 10px)", position: "relative" }}>
            <div style={{ position: "absolute", inset: 0, background: `linear-gradient(120deg, ${MINT}, ${MAGENTA})`, clipPath: `inset(0 ${100 - paint * 100}% 0 0)`, opacity: 0.85 }} />
          </div>
          <div style={{ padding: 14, display: "flex", flexDirection: "column", gap: 8 }}>
            <div style={{ width: 130, height: 10, background: ink, borderRadius: 5 }} />
            <div style={{ width: 190, height: 7, background: "rgba(20,21,26,0.24)", borderRadius: 4 }} />
            <div style={{ width: 150, height: 7, background: "rgba(20,21,26,0.24)", borderRadius: 4 }} />
          </div>
        </div>
      );
    }
    if (k.id === "input") return (
      <div key={k.id} style={{ ...base, background: "#fff", borderRadius: 10, border: "1px solid rgba(20,21,26,0.2)", display: "flex", alignItems: "center", padding: "0 14px", gap: 8 }}>
        <div style={{ width: 96, height: 8, background: "rgba(20,21,26,0.3)", borderRadius: 4 }} />
        <div style={{ width: 2, height: 22, background: ink, opacity: Math.sin(T * 8) > 0 ? 1 : 0.15 }} />
      </div>
    );
    if (k.id === "btn") {
      const press = 1 - 0.06 * Math.max(0, Math.sin((T - (C.craft + 4.4)) * 9)) * (T > C.craft + 4.4 && T < C.craft + 4.75 ? 1 : 0);
      return (
        <div key={k.id} style={{ ...base, transform: `scale(${s * press}) rotate(${rot}deg)`, background: MINT, borderRadius: 10, display: "flex", alignItems: "center", justifyContent: "center", color: INK, fontFamily: "'Figtree', sans-serif", fontWeight: 700, fontSize: 19 }}>Launch</div>
      );
    }
    if (k.id === "toggle") {
      const on = MOTION.pop(3, 29, t0 + 0.5, t0 + 1.0)(T);
      return (
        <div key={k.id} style={{ ...base, background: "rgba(20,21,26,0.12)", borderRadius: 999, border: "1px solid rgba(20,21,26,0.16)" }}>
          <div style={{ position: "absolute", top: 3, left: on, width: 26, height: 26, borderRadius: 999, background: MAGENTA }} />
        </div>
      );
    }
    const bars = [0.35, 0.62, 0.48, 0.86, 0.7, 1];
    return (
      <div key={k.id} style={{ ...base, background: "#fff", borderRadius: 10, border: "1px solid rgba(20,21,26,0.14)", display: "flex", alignItems: "flex-end", gap: 7, padding: 10 }}>
        {bars.map((b, j) => (
          <div key={j} style={{ flex: 1, borderRadius: 3, background: j === 5 ? MINT : "rgba(20,21,26,0.22)", height: 38 * b * MOTION.pop(0, 1, t0 + 0.3 + j * 0.07, t0 + 0.9 + j * 0.07)(T) }} />
        ))}
      </div>
    );
  });
}

function Products({ T, C }) {
  const P1 = { x: 420, y: 700, w: 560, h: 400, at: 0.2 };
  const P2 = { x: 1470, y: 640, w: 260, h: 520, at: 2.0 };
  const P3 = { x: 2220, y: 730, w: 560, h: 340, at: 3.8 };
  const collapse = (o) => {
    const c = MOTION.draw(0, 1, C.launch + 0.5, C.launch + 1.8)(T);
    return { s: 1 - 0.75 * c, dx: (CX - (o.x + o.w / 2)) * c, dy: (CY - (o.y + o.h / 2)) * c, a: 1 - c };
  };
  const shell = (o, extra) => {
    const t0 = C.build + o.at;
    const c = collapse(o);
    const a = MOTION.enter(0, 1, t0, t0 + 0.5)(T) * c.a;
    const s = MOTION.pop(0.9, 1, t0, t0 + 0.7)(T) * c.s;
    return {
      position: "absolute", left: o.x, top: o.y, width: o.w, height: o.h, opacity: a,
      transform: `translate(${c.dx}px, ${c.dy}px) scale(${s})`, transformOrigin: "50% 50%",
      ...extra,
    };
  };
  const row = (t0, i) => MOTION.enter(0, 1, t0 + 0.35 + i * 0.09, t0 + 0.85 + i * 0.09)(T);
  const ink = "rgba(20,21,26,0.9)";
  return (
    <React.Fragment>
      <div style={shell(P1, { background: "#fff", borderRadius: 16, overflow: "hidden", border: "1px solid rgba(20,21,26,0.16)", boxShadow: "0 40px 80px -40px rgba(0,0,0,0.45)" })}>
        <div style={{ height: 34, background: "rgba(20,21,26,0.06)", display: "flex", alignItems: "center", gap: 6, padding: "0 12px" }}>
          {[MAGENTA, MINT, "rgba(20,21,26,0.2)"].map((c, j) => <div key={j} style={{ width: 9, height: 9, borderRadius: 999, background: c }} />)}
          <div style={{ marginLeft: 10, width: 180, height: 10, borderRadius: 5, background: "rgba(20,21,26,0.12)" }} />
        </div>
        <div style={{ padding: 22 }}>
          <div style={{ opacity: row(C.build + P1.at, 0), width: 320, height: 26, background: ink, borderRadius: 6 }} />
          <div style={{ opacity: row(C.build + P1.at, 1), width: 240, height: 12, background: "rgba(20,21,26,0.25)", borderRadius: 6, marginTop: 12 }} />
          <div style={{ display: "flex", gap: 14, marginTop: 22 }}>
            {[0, 1, 2].map(j => (
              <div key={j} style={{ opacity: row(C.build + P1.at, 2 + j), flex: 1, height: 150, borderRadius: 10, background: j === 1 ? `linear-gradient(140deg, ${MINT}, ${MAGENTA})` : "repeating-linear-gradient(135deg, rgba(20,21,26,0.08) 0 2px, transparent 2px 10px)", border: "1px solid rgba(20,21,26,0.1)" }} />
            ))}
          </div>
        </div>
      </div>

      <div style={shell(P2, { background: INK, borderRadius: 34, border: "6px solid rgba(20,21,26,0.9)", overflow: "hidden", boxShadow: "0 40px 80px -40px rgba(0,0,0,0.55)" })}>
        <div style={{ padding: 18, display: "flex", flexDirection: "column", gap: 12, height: "100%" }}>
          <div style={{ opacity: row(C.build + P2.at, 0), display: "flex", justifyContent: "space-between", alignItems: "center" }}>
            <div style={{ width: 90, height: 12, background: "rgba(245,246,247,0.85)", borderRadius: 6 }} />
            <div style={{ width: 22, height: 22, borderRadius: 999, background: MINT }} />
          </div>
          {[0, 1, 2, 3].map(j => (
            <div key={j} style={{ opacity: row(C.build + P2.at, 1 + j), display: "flex", gap: 10, alignItems: "center", padding: 10, borderRadius: 12, background: "rgba(255,255,255,0.06)" }}>
              <div style={{ width: 34, height: 34, borderRadius: 9, background: j === 0 ? MAGENTA : "rgba(255,255,255,0.16)" }} />
              <div style={{ flex: 1, display: "flex", flexDirection: "column", gap: 6 }}>
                <div style={{ width: "70%", height: 8, background: "rgba(245,246,247,0.7)", borderRadius: 4 }} />
                <div style={{ width: "45%", height: 6, background: "rgba(245,246,247,0.3)", borderRadius: 4 }} />
              </div>
            </div>
          ))}
          <div style={{ opacity: row(C.build + P2.at, 5), marginTop: "auto", height: 44, borderRadius: 12, background: MINT }} />
        </div>
      </div>

      <div style={shell(P3, { background: "#141519", borderRadius: 16, overflow: "hidden", border: "1px solid rgba(255,255,255,0.14)", boxShadow: "0 40px 80px -40px rgba(0,0,0,0.55)" })}>
        <div style={{ position: "absolute", inset: 0, background: "repeating-linear-gradient(90deg, rgba(255,255,255,0.05) 0 1px, transparent 1px 26px)" }} />
        <div style={{ position: "absolute", left: 18, top: 18, right: 18, display: "flex", gap: 14, alignItems: "center", opacity: row(C.build + P3.at, 0) }}>
          <div style={{ flex: 1, height: 14, borderRadius: 7, background: "rgba(255,255,255,0.12)", overflow: "hidden" }}>
            <div style={{ height: "100%", width: `${MOTION.draw(20, 78, C.build + P3.at + 0.5, C.build + P3.at + 1.7)(T)}%`, background: MINT }} />
          </div>
          <div style={{ fontFamily: "'Space Mono', monospace", color: CHALK, fontSize: 18, letterSpacing: "0.16em" }}>SCORE {Math.round(MOTION.draw(0, 4820, C.build + P3.at + 0.4, C.build + P3.at + 1.9)(T))}</div>
        </div>
        <div style={{ position: "absolute", left: 34, bottom: 34, width: 92, height: 92, borderRadius: 999, border: `3px solid ${MINT}`, opacity: 0.8 * row(C.build + P3.at, 2) }}>
          <div style={{ position: "absolute", left: 28, top: 28, width: 34, height: 34, borderRadius: 999, background: MINT, opacity: 0.55 }} />
        </div>
        <div style={{ position: "absolute", right: 40, bottom: 40, display: "flex", gap: 12, opacity: row(C.build + P3.at, 3) }}>
          {[MAGENTA, "rgba(255,255,255,0.18)"].map((c, j) => <div key={j} style={{ width: 46, height: 46, borderRadius: 999, background: c }} />)}
        </div>
      </div>
    </React.Fragment>
  );
}

const WORD = "PixelCraftsLab";
function Identity({ T, C }) {
  const start = C.launch + 3.0;
  const a = MOTION.enter(0, 1, start - 0.2, start + 0.4)(T);
  if (a < 0.01) return null;
  const line = MOTION.draw(0, 1, start + 1.3, start + 2.1)(T);
  const tag = "Ideas. Build. Launch.";
  const tp = MOTION.draw(0, tag.length, start + 1.5, start + 2.5)(T);
  return (
    <div style={{ position: "absolute", left: CX, top: 900, transform: "translateX(-50%)", textAlign: "center", width: 1400 }}>
      <div style={{
        fontFamily: "'Bricolage Grotesque', sans-serif", fontWeight: 800, fontSize: 104,
        letterSpacing: "-0.045em", color: "#F5F6F7", display: "flex", justifyContent: "center",
      }}>
        {WORD.split("").map((ch, i) => {
          const s = start + i * 0.055;
          return (
            <span key={i} style={{
              display: "inline-block",
              opacity: MOTION.enter(0, 1, s, s + 0.35)(T),
              transform: `translateY(${MOTION.pop(46, 0, s, s + 0.55)(T)}px)`,
            }}>{ch}</span>
          );
        })}
      </div>
      <div style={{ height: 2, background: MINT, width: `${line * 46}%`, margin: "26px auto 0", opacity: 0.85 }} />
      <div style={{
        marginTop: 26, fontFamily: "'Space Mono', monospace", fontSize: 27,
        letterSpacing: "0.32em", textTransform: "uppercase", color: "rgba(245,246,247,0.66)",
      }}>{tag.slice(0, Math.round(tp))}</div>
    </div>
  );
}

function Cursor({ T, C, sc }) {
  const K = [
    [C.exp + 0.6, 1340, 755], [C.exp + 1.4, 1630, 815], [C.exp + 2.1, 1920, 815],
    [C.exp + 2.9, 1680, 1195], [C.exp + 4.1, 1460, 935], [C.craft + 0.6, 1340, 1015],
    [C.craft + 1.4, 1680, 975], [C.craft + 3.0, 1860, 1125], [C.craft + 4.4, 1700, 941],
    [C.craft + 5.6, 2260, 1515],
  ];
  const [x, y] = kf(T, K);
  const a = MOTION.enter(0, 1, C.exp + 0.4, C.exp + 0.8)(T) * MOTION.draw(1, 0, C.craft + 5.0, C.craft + 5.7)(T);
  if (a < 0.01) return null;
  const clicks = [C.exp + 2.9, C.craft + 1.4, C.craft + 4.4];
  return (
    <React.Fragment>
      {clicks.map((c, i) => {
        const p = MOTION.draw(0, 1, c, c + 0.6)(T);
        if (p <= 0.01 || p >= 0.99) return null;
        const [cx2, cy2] = kf(c, K);
        const rr = (20 + p * 90) / sc;
        return <div key={i} style={{ position: "absolute", left: cx2, top: cy2, width: rr, height: rr, marginLeft: -rr / 2, marginTop: -rr / 2, borderRadius: 999, border: `${2 / sc}px solid ${MINT}`, opacity: 1 - p }} />;
      })}
      <svg width={42 / sc} height={48 / sc} viewBox="0 0 26 30" style={{ position: "absolute", left: x, top: y, opacity: a, filter: "drop-shadow(0 4px 10px rgba(0,0,0,0.5))" }}>
        <path d="M2 1 L2 23 L8 17.5 L12 27 L16 25 L12 16 L20 15 Z" fill="#F5F6F7" stroke={INK} strokeWidth="1.6" strokeLinejoin="round" />
      </svg>
    </React.Fragment>
  );
}

function Annotations({ T, C, sc }) {
  const n = [
    { x: CX + 30 / sc, y: CY - 42 / sc, t: "idea.px", at: 0.9, out: C.exp + 1.6, c: MINT },
    { x: 1000, y: 620, t: "grid / 40", at: C.exp + 1.0, out: C.craft + 1.2, c: "rgba(245,246,247,0.55)" },
    { x: 1880, y: 700, t: "rough — fix later", at: C.exp + 2.4, out: C.craft + 0.6, c: "rgba(245,246,247,0.55)" },
    { x: 1000, y: 620, t: "material / 67", at: C.craft + 1.4, out: C.build - 0.2, c: "rgba(20,21,26,0.45)" },
    { x: 1880, y: 1075, t: "snap ✓", at: C.craft + 3.5, out: C.build - 0.2, c: "rgba(20,21,26,0.45)" },
    { x: 420, y: 660, t: "web platform", at: C.build + 0.5, out: C.launch + 0.4, c: "rgba(20,21,26,0.45)" },
    { x: 1470, y: 600, t: "utility app", at: C.build + 2.3, out: C.launch + 0.4, c: "rgba(20,21,26,0.45)" },
    { x: 2220, y: 690, t: "offline game", at: C.build + 4.1, out: C.launch + 0.4, c: "rgba(20,21,26,0.45)" },
  ];
  return n.map((o, i) => {
    const a = MOTION.enter(0, 1, o.at, o.at + 0.4)(T) * MOTION.draw(1, 0, o.out, o.out + 0.5)(T);
    if (a < 0.02) return null;
    const chars = Math.round(MOTION.draw(0, o.t.length, o.at, o.at + 0.5)(T));
    return <Mono key={i} x={o.x} y={o.y} text={o.t.slice(0, chars)} opacity={a} color={o.c} size={21 / sc} />;
  });
}

function Overlay({ T, C, total, showLabels }) {
  const rail = [
    { k: "PIXEL", from: 0, to: C.craft },
    { k: "CRAFT", from: C.craft, to: C.build },
    { k: "LAB", from: C.build, to: total },
  ];
  const secs = [
    { n: "01", t: "Idea", at: 0 }, { n: "02", t: "Experiment", at: C.exp },
    { n: "03", t: "Craft", at: C.craft }, { n: "04", t: "Build", at: C.build },
    { n: "05", t: "Launch", at: C.launch },
  ];
  const cur = secs.filter(s => T >= s.at).pop();
  const out = MOTION.draw(1, 0, C.launch + 2.4, C.launch + 3.2)(T);
  if (!showLabels) return null;
  return (
    <div style={{ position: "absolute", inset: 0, pointerEvents: "none", opacity: out }}>
      <div style={{ position: "absolute", left: 56, top: 52, display: "flex", gap: 14, alignItems: "center", fontFamily: "'Space Mono', monospace", fontSize: 17, letterSpacing: "0.3em" }}>
        {rail.map((r, i) => {
          const on = T >= r.from && T < r.to;
          return (
            <React.Fragment key={r.k}>
              {i > 0 && <span style={{ color: "rgba(245,246,247,0.25)" }}>→</span>}
              <span style={{ color: on ? MINT : "rgba(245,246,247,0.3)", transition: "none" }}>{r.k}</span>
            </React.Fragment>
          );
        })}
      </div>
      <div style={{ position: "absolute", left: 56, bottom: 52, display: "flex", alignItems: "baseline", gap: 16 }}>
        <span style={{ fontFamily: "'Space Mono', monospace", fontSize: 15, letterSpacing: "0.22em", color: MAGENTA }}>{cur ? cur.n : "01"}</span>
        <span style={{ fontFamily: "'Bricolage Grotesque', sans-serif", fontWeight: 500, fontSize: 34, letterSpacing: "-0.02em", color: "rgba(245,246,247,0.9)" }}>{cur ? cur.t : ""}</span>
      </div>
      <div style={{ position: "absolute", right: 56, bottom: 56, width: 240, height: 2, background: "rgba(245,246,247,0.16)" }}>
        <div style={{ height: "100%", width: `${Math.min(1, T / total) * 100}%`, background: "rgba(245,246,247,0.7)" }} />
      </div>
    </div>
  );
}

function Piece({ showLabels }) {
  const { T, CUES, authoredTotal } = useComposition();
  const C = useMemo(() => ({
    idea: CUES.Idea, exp: CUES.Experiment, craft: CUES.Craft, build: CUES.Build, launch: CUES.Launch,
  }), [CUES]);

  const [fx, fy, sc] = kf(T, [
    [0, CX, CY, 3.9], [1.8, CX, CY, 3.45], [C.exp, CX, CY, 2.8], [C.exp + 2.5, 1560, 880, 2.15],
    [C.craft, CX, CY, 1.7], [C.craft + 3, CX, CY, 1.35], [C.build - 0.4, CX, CY, 1.15],
    [C.build + 1.2, 700, CY, 1.5], [C.build + 3.2, CX, CY, 1.5], [C.build + 5.2, 2500, CY, 1.5],
    [C.launch + 0.6, CX, CY, 0.6], [C.launch + 2.6, CX, CY, 0.95], [C.launch + 4, CX, CY, 1.0],
    [authoredTotal, CX, CY, 1.03],
  ]);

  return (
    <div style={{ position: "absolute", inset: 0, background: INK, overflow: "hidden", fontFamily: "'Figtree', sans-serif" }}>
      <div style={{
        position: "absolute", left: 0, top: 0, width: BW, height: BH, transformOrigin: "0 0",
        transform: `translate(${960 - fx * sc}px, ${540 - fy * sc}px) scale(${sc})`,
      }}>
        <Grid T={T} C={C} />
        <Sheet T={T} C={C} />
        <Guides T={T} C={C} />
        <Shot from={C.craft - 1} to={C.build + 1.4}><CraftKit T={T} C={C} /></Shot>
        <Shot from={C.build - 0.6} to={C.launch + 2.2}><Products T={T} C={C} /></Shot>
        <Spark T={T} sc={sc} />
        <PixelField T={T} C={C} />
        <Annotations T={T} C={C} sc={sc} />
        <Cursor T={T} C={C} sc={sc} />
        <Shot from={C.launch + 2.6} to={authoredTotal + 1}><Identity T={T} C={C} /></Shot>
      </div>
      <div style={{ position: "absolute", inset: 0, pointerEvents: "none", background: "radial-gradient(ellipse at center, transparent 46%, rgba(0,0,0,0.55) 100%)" }} />
      <div style={{ position: "absolute", inset: 0, pointerEvents: "none", opacity: 0.05, backgroundImage: "radial-gradient(circle, #fff 0.6px, transparent 0.6px)", backgroundSize: "3px 3px" }} />
      <Overlay T={T} C={C} total={authoredTotal} showLabels={showLabels} />
    </div>
  );
}

function PCLBrand() {
  const [t, setTweak] = useTweaks(window.PCL_TWEAKS);
  return (
    <React.Fragment>
      <CompositionStage width={1920} height={1080} scenes={window.OM_SCENES} playback={window.OM_PLAYBACK} bg={INK}>
        <Piece showLabels={t.sectionLabels} />
      </CompositionStage>
      <TweaksPanel>
        <TweakSection label="Sequence" />
        <TweakToggle label="Stage labels" value={t.sectionLabels} onChange={v => setTweak("sectionLabels", v)} />
        <TweakToggle label="Motion editor" value={t.motionEditor} onChange={v => setTweak("motionEditor", v)} />
      </TweaksPanel>
    </React.Fragment>
  );
}

window.PCLBrand = PCLBrand;
