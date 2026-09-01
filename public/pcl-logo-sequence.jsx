/* PixelCraftsLab — the logo builds itself.
   Every visible mark is a layer cut from the supplied artwork, so the
   final frame is the exact logo, untouched. */

const { useMemo } = React;

const PAPER = "#F4F2EE";
const INK = "#16141A";
const PURPLE = "#5B2394";
const PURPLE_LT = "#7B4FBF";
const ORANGE = "#EE5A1F";

/* board + logo placement (logo art is 960x1080, drawn at 2/3 size) */
const BW = 3200, BH = 1800;
const LX = 1280, LY = 470, LS = 2 / 3;
const bx = lx => LX + lx * LS;
const by = ly => LY + ly * LS;

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

function wedge(cx, cy, a0, a1, R) {
  const pts = [[cx, cy]];
  for (let i = 0; i <= 28; i++) {
    const a = ((a0 + (a1 - a0) * (i / 28)) * Math.PI) / 180;
    pts.push([cx + Math.cos(a) * R, cy + Math.sin(a) * R]);
  }
  return `polygon(${pts.map(p => `${p[0].toFixed(1)}px ${p[1].toFixed(1)}px`).join(",")})`;
}
const boxClip = (x0, y0, x1, y1) => `polygon(${x0}px ${y0}px, ${x1}px ${y0}px, ${x1}px ${y1}px, ${x0}px ${y1}px)`;

function rand(seed) { let a = seed >>> 0; return () => { a = (a + 0x6d2b79f5) >>> 0; let t = Math.imul(a ^ (a >>> 15), 1 | a); t = (t + Math.imul(t ^ (t >>> 7), 61 | t)) ^ t; return ((t ^ (t >>> 14)) >>> 0) / 4294967296; }; }

/* loose creative material — three of these become the logo's dots */
const GRAINS = (() => {
  const r = rand(4471), out = [];
  const homes = [[441, 896], [436, 970], [391, 939]];
  for (let i = 0; i < 30; i++) {
    const dot = i < 3;
    out.push({
      i, dot, home: dot ? homes[i] : null,
      c: dot ? (i === 2 ? INK : ORANGE) : (r() < 0.62 ? PURPLE : r() < 0.6 ? ORANGE : INK),
      s: dot ? 16 : 8 + Math.round(r() * 12),
      born: 0.9 + r() * 3.6,
      ox: (r() - 0.5) * 1500, oy: (r() - 0.5) * 1200,
      gx: 200 + Math.round(r() * 34) * 40, gy: 120 + Math.round(r() * 26) * 40,
      ph: r() * 6.28, sp: 0.4 + r() * 0.9,
    });
  }
  return out;
})();

const IMG = { position: "absolute", left: 0, top: 0, width: 960, height: 1080, display: "block" };
const layer = (src, clip, extra) => (
  <img src={src} alt="" draggable="false" style={{ ...IMG, clipPath: clip, WebkitClipPath: clip, ...extra }} />
);

/* ---------- ACT 1 — pixel ---------- */
function Grains({ T, C, sc }) {
  return GRAINS.map(g => {
    const born = g.born;
    if (T < born) return null;
    let a = MOTION.enter(0, 1, born, born + 0.35)(T);
    let x = bx(g.gx * 0 + 0) + 0, y = 0;
    const gxb = LX + g.gx * LS, gyb = LY + g.gy * LS;
    const settle = MOTION.enter(0, 1, born, born + 0.9)(T);
    x = gxb + g.ox * (1 - settle);
    y = gyb + g.oy * (1 - settle);
    const drift = MOTION.draw(1, 0, C.assemble - 0.6, C.assemble + 1.2)(T);
    x += Math.sin(T * g.sp + g.ph) * 26 * drift;
    y += Math.cos(T * g.sp * 0.8 + g.ph) * 20 * drift;
    let size = g.s;
    if (g.dot) {
      const fly = MOTION.pop(0, 1, C.assemble + 2.0 + g.i * 0.28, C.assemble + 2.9 + g.i * 0.28)(T);
      x = x + (bx(g.home[0]) - x) * fly;
      y = y + (by(g.home[1]) - y) * fly;
      size = g.s + (10 - g.s) * fly;
      a *= 1 - MOTION.draw(0, 1, C.assemble + 2.75 + g.i * 0.28, C.assemble + 3.0 + g.i * 0.28)(T);
    } else {
      const out = C.craft + 0.6 + (g.i % 10) * 0.34;
      a *= MOTION.draw(1, 0, out, out + 0.5)(T);
    }
    if (a < 0.02) return null;
    return <div key={g.i} style={{ position: "absolute", left: x, top: y, width: size, height: size, marginLeft: -size / 2, marginTop: -size / 2, background: g.c, opacity: a }} />;
  });
}

/* ---------- construction furniture ---------- */
function Guides({ T, C, sc }) {
  const on = MOTION.enter(0, 1, 1.4, 2.2)(T) * MOTION.draw(1, 0, C.launch + 0.6, C.launch + 1.6)(T);
  if (on < 0.02) return null;
  const v = [275, 372, 628, 755, 812];
  const h = [59, 478, 603, 947];
  const w = 1 / sc;
  return (
    <div style={{ position: "absolute", inset: 0, opacity: on * 0.5 }}>
      {v.map(p => <div key={"v" + p} style={{ position: "absolute", left: bx(p), top: LY - 260, width: w, height: 1280, background: PURPLE, opacity: 0.35 }} />)}
      {h.map(p => <div key={"h" + p} style={{ position: "absolute", left: LX - 300, top: by(p), height: w, width: 1240, background: PURPLE, opacity: 0.28 }} />)}
    </div>
  );
}

function Marks({ T, C, sc }) {
  const a = MOTION.enter(0, 1, C.assemble + 3.4, C.assemble + 3.9)(T);
  const anchors = [[275, 59], [628, 59], [372, 478], [755, 620], [812, 947], [413, 896], [233, 103]];
  const w = 1.6 / sc, L = 22 / sc;
  return (
    <div style={{ position: "absolute", inset: 0 }}>
      {anchors.map((p, i) => {
        const o = a * MOTION.draw(1, 0, C.launch + 0.2 + i * 0.16, C.launch + 0.7 + i * 0.16)(T);
        if (o < 0.02) return null;
        const s = 13 / sc;
        return <div key={i} style={{ position: "absolute", left: bx(p[0]), top: by(p[1]), width: s, height: s, marginLeft: -s / 2, marginTop: -s / 2, border: `${w}px solid ${PURPLE}`, background: PAPER, opacity: o }} />;
      })}
      {[[0, 0], [1, 0], [0, 1], [1, 1]].map((q, i) => {
        const o = a * MOTION.draw(1, 0, C.launch + 1.4, C.launch + 2.1)(T);
        if (o < 0.02) return null;
        const px = LX - 120 + q[0] * (960 * LS + 240);
        const py = LY - 90 + q[1] * (1080 * LS + 180);
        return (
          <div key={"c" + i} style={{ position: "absolute", left: px, top: py, opacity: o * 0.7 }}>
            <div style={{ position: "absolute", left: -L, top: 0, width: L * 2, height: w, background: INK }} />
            <div style={{ position: "absolute", left: 0, top: -L, height: L * 2, width: w, background: INK }} />
          </div>
        );
      })}
    </div>
  );
}

/* ---------- ACT 2 — the P ---------- */
function TheP({ T, C, sc }) {
  const pxA = MOTION.pop(0, 1, C.pixel + 2.0, C.pixel + 2.6)(T);
  const pxB = MOTION.pop(0, 1, C.pixel + 2.6, C.pixel + 3.2)(T);

  const stemLine = MOTION.draw(0, 1, C.craft + 0.15, C.craft + 1.15)(T);
  const stemOpen = MOTION.draw(0, 1, C.craft + 1.0, C.craft + 2.1)(T);
  const stemClip = boxClip(338 - 34 * stemOpen, 59, 338 + 34 * stemOpen, 59 + 880 * Math.max(stemLine, stemOpen));

  const paint = MOTION.draw(0, 1, C.craft + 2.5, C.craft + 4.6)(T);
  const bowlClip = wedge(372, 268, -128, 128, 760 * (paint > 0.001 ? 1 : 0)).replace("polygon(", "polygon(");
  const bowlWedge = wedge(372, 268, -128, -128 + 256 * paint, 780);

  const trace = MOTION.draw(0, 1, C.idea + 0.5, C.idea + 2.4)(T);
  const iconClip = boxClip(330, 120, 560, 124 + 330 * trace);
  const bulb = MOTION.draw(0, 1, C.idea + 2.2, C.idea + 3.0)(T);
  const gearLock = MOTION.pop(7, 0, C.idea + 1.6, C.idea + 2.6)(T);

  const brushX = 372 + Math.cos((-128 + 256 * paint) * Math.PI / 180) * 300;
  const brushY = 268 + Math.sin((-128 + 256 * paint) * Math.PI / 180) * 300;

  return (
    <React.Fragment>
      {pxA > 0.01 && layer("assets/L-pixels.png", boxClip(220, 95, 280, 150), { opacity: Math.min(1, pxA), transform: `scale(${0.6 + 0.4 * Math.min(1, pxA)})`, transformOrigin: "250px 120px" })}
      {pxB > 0.01 && layer("assets/L-pixels.png", boxClip(268, 50, 340, 105), { opacity: Math.min(1, pxB), transform: `scale(${0.6 + 0.4 * Math.min(1, pxB)})`, transformOrigin: "300px 78px" })}

      {stemLine > 0.001 && stemOpen < 0.99 && (
        <div style={{ position: "absolute", left: 337, top: 59, width: 2 / (sc * LS), height: 880 * stemLine, background: PURPLE }} />
      )}
      {stemOpen > 0.001 && layer("assets/L-stem.png", stemClip)}

      {paint > 0.001 && (
        <React.Fragment>
          {layer("assets/L-bowl.png", bowlWedge)}
          {paint < 0.99 && (
            <div style={{ position: "absolute", left: brushX, top: brushY, width: 150, height: 150, marginLeft: -75, marginTop: -75, borderRadius: "50%", background: `radial-gradient(circle, ${PURPLE_LT} 0%, rgba(123,79,191,0) 70%)`, opacity: 0.85, filter: "blur(6px)" }} />
          )}
        </React.Fragment>
      )}

      {trace > 0.001 && (
        <div style={{ position: "absolute", inset: 0, transform: `rotate(${gearLock}deg)`, transformOrigin: "445px 250px" }}>
          {bulb > 0.01 && (
            <div style={{ position: "absolute", left: 400, top: 210, width: 300, height: 300, marginLeft: -150, marginTop: -150, borderRadius: "50%", background: "radial-gradient(circle, rgba(255,214,120,0.85) 0%, rgba(255,214,120,0) 68%)", opacity: bulb * (1 - 0.45 * MOTION.draw(0, 1, C.idea + 3.0, C.idea + 4.2)(T)) }} />
          )}
          {layer("assets/L-icon.png", iconClip)}
          {trace < 0.999 && (
            <div style={{ position: "absolute", left: 330, top: 124 + 330 * trace, width: 230, height: 2 / (sc * LS), background: ORANGE, opacity: 0.9 }} />
          )}
        </div>
      )}
    </React.Fragment>
  );
}

/* ---------- ACT 4 — the orange C ---------- */
function TheC({ T, C, sc }) {
  const rough = MOTION.draw(0, 1, C.curve + 0.2, C.curve + 1.5)(T);
  const roughOut = MOTION.draw(1, 0, C.curve + 1.7, C.curve + 2.6)(T);
  const crisp = MOTION.draw(0, 1, C.curve + 1.3, C.curve + 2.9)(T);
  const seg = MOTION.pop(0, 1, C.curve + 3.3, C.curve + 4.0)(T);
  const handles = MOTION.enter(0, 1, C.curve + 2.2, C.curve + 2.6)(T) * MOTION.draw(1, 0, C.curve + 3.6, C.curve + 4.2)(T);
  const w = 1.6 / (sc * LS);
  return (
    <React.Fragment>
      {rough > 0.001 && roughOut > 0.01 && (
        <svg viewBox="0 0 960 1080" width={960} height={1080} style={{ position: "absolute", inset: 0, opacity: roughOut * 0.9 }}>
          <path d="M 742 596 C 700 512, 596 470, 512 500 C 404 536, 356 654, 396 752 C 428 830, 508 866, 566 848"
            pathLength="1" fill="none" stroke={ORANGE} strokeWidth="62" strokeLinecap="round"
            strokeDasharray="1" strokeDashoffset={1 - rough} opacity="0.55" />
        </svg>
      )}
      {crisp > 0.001 && layer("assets/L-orange.png", wedge(581, 668, -50, -50 - 262 * crisp, 620))}
      {seg > 0.001 && layer("assets/L-orange2.png", null, { opacity: Math.min(1, seg), transform: `translate(${(1 - Math.min(1, seg)) * 46}px, ${(1 - Math.min(1, seg)) * -26}px)` })}
      {handles > 0.02 && (
        <div style={{ position: "absolute", inset: 0, opacity: handles }}>
          {[[742, 596], [512, 500], [396, 752], [566, 848]].map((p, i) => (
            <div key={i} style={{ position: "absolute", left: p[0], top: p[1], width: 14, height: 14, marginLeft: -7, marginTop: -7, border: `${w}px solid ${PURPLE}`, background: PAPER }} />
          ))}
        </div>
      )}
    </React.Fragment>
  );
}

/* ---------- ACT 5 — the black component ---------- */
function TheL({ T, C }) {
  const bands = [
    { c: boxClip(540, 598, 600, 830), d: 0.0, from: [0, -70] },
    { c: boxClip(540, 830, 720, 952), d: 0.35, from: [-60, 40] },
    { c: boxClip(720, 700, 830, 952), d: 0.7, from: [70, 30] },
    { c: boxClip(600, 598, 830, 700), d: 1.0, from: [50, -40] },
  ];
  return bands.map((b, i) => {
    const t0 = C.assemble + 0.2 + b.d;
    const p = MOTION.pop(0, 1, t0, t0 + 0.5)(T);
    if (p < 0.01) return null;
    const q = 1 - Math.min(1, p);
    return <React.Fragment key={i}>{layer("assets/L-black.png", b.c, { opacity: Math.min(1, p * 1.4), transform: `translate(${b.from[0] * q}px, ${b.from[1] * q}px)` })}</React.Fragment>;
  });
}

function Dots({ T, C }) {
  return ["assets/L-dot1.png", "assets/L-dot2.png", "assets/L-dot3.png"].map((s, i) => {
    const t0 = C.assemble + 2.75 + i * 0.28;
    const p = MOTION.pop(0, 1, t0, t0 + 0.5)(T);
    if (p < 0.01) return null;
    return <React.Fragment key={s}>{layer(s, null, { opacity: Math.min(1, p * 1.6), transform: `scale(${0.4 + 0.6 * Math.min(1, p)})`, transformOrigin: "430px 930px" })}</React.Fragment>;
  });
}

/* ---------- ACT 6 — the lockup ---------- */
const WORD = "PixelCraftsLab";
function Lockup({ T, C, total }) {
  const start = C.launch + 2.3;
  if (T < start - 0.3) return null;
  const rule = MOTION.draw(0, 1, start + 1.0, start + 1.9)(T);
  const tag = "Ideas. Build. Launch.";
  const tp = MOTION.draw(0, tag.length, start + 1.4, start + 2.6)(T);
  return (
    <div style={{ position: "absolute", left: 1600, top: 1265, transform: "translateX(-50%)", width: 1600, textAlign: "center" }}>
      <div style={{ display: "flex", justifyContent: "center", fontFamily: "'Bricolage Grotesque', sans-serif", fontWeight: 800, fontSize: 96, letterSpacing: "-0.045em", color: INK }}>
        {WORD.split("").map((ch, i) => {
          const s = start + i * 0.05;
          return <span key={i} style={{ display: "inline-block", opacity: MOTION.enter(0, 1, s, s + 0.32)(T), transform: `translateY(${MOTION.pop(38, 0, s, s + 0.5)(T)}px)` }}>{ch}</span>;
        })}
      </div>
      <div style={{ height: 3, width: `${rule * 38}%`, background: ORANGE, margin: "22px auto 0" }} />
      <div style={{ marginTop: 24, fontFamily: "'Space Mono', monospace", fontSize: 26, letterSpacing: "0.34em", textTransform: "uppercase", color: "rgba(22,20,26,0.6)" }}>{tag.slice(0, Math.round(tp))}</div>
    </div>
  );
}

function ActLabel({ T, C, total, show }) {
  if (!show) return null;
  const acts = [
    { n: "01", t: "Pixel", at: 0 }, { n: "02", t: "Craft", at: C.craft },
    { n: "03", t: "Idea", at: C.idea }, { n: "04", t: "Curve", at: C.curve },
    { n: "05", t: "Assemble", at: C.assemble }, { n: "06", t: "Launch", at: C.launch },
  ];
  const cur = acts.filter(a => T >= a.at).pop() || acts[0];
  const o = MOTION.draw(1, 0, C.launch + 1.6, C.launch + 2.3)(T);
  return (
    <div style={{ position: "absolute", left: 56, bottom: 50, display: "flex", alignItems: "baseline", gap: 14, opacity: o }}>
      <span style={{ fontFamily: "'Space Mono', monospace", fontSize: 14, letterSpacing: "0.22em", color: ORANGE }}>{cur.n}</span>
      <span style={{ fontFamily: "'Space Mono', monospace", fontSize: 14, letterSpacing: "0.22em", textTransform: "uppercase", color: "rgba(22,20,26,0.5)" }}>{cur.t}</span>
    </div>
  );
}

function Piece({ showLabels }) {
  const { T, CUES, authoredTotal } = useComposition();
  const C = useMemo(() => ({
    pixel: CUES.Pixel, craft: CUES.Craft, idea: CUES.Idea,
    curve: CUES.Curve, assemble: CUES.Assemble, launch: CUES.Launch,
  }), [CUES]);

  const [fx, fy, sc] = kf(T, [
    [0, 1447, 550, 7.0], [1.3, 1447, 552, 6.2], [2.4, 1455, 566, 4.6], [C.craft - 0.3, 1495, 690, 2.3],
    [C.craft + 2.2, 1505, 800, 1.75], [C.craft + 4.8, 1560, 745, 1.5],
    [C.idea + 0.4, 1577, 657, 2.55], [C.idea + 3.6, 1580, 655, 2.85],
    [C.curve + 0.4, 1667, 917, 1.75], [C.curve + 4.2, 1650, 900, 1.5],
    [C.assemble + 0.3, 1706, 990, 1.6], [C.assemble + 3.2, 1600, 1010, 1.25],
    [C.launch + 0.4, 1600, 860, 1.0], [C.launch + 2.6, 1600, 950, 0.93],
    [authoredTotal, 1600, 962, 0.9],
  ]);

  return (
    <div style={{ position: "absolute", inset: 0, background: PAPER, overflow: "hidden" }}>
      <div style={{
        position: "absolute", left: 0, top: 0, width: BW, height: BH, transformOrigin: "0 0",
        transform: `translate(${960 - fx * sc}px, ${540 - fy * sc}px) scale(${sc})`,
      }}>
        <div style={{
          position: "absolute", inset: 0,
          backgroundImage: `radial-gradient(circle, rgba(91,35,148,0.30) 1.2px, transparent 1.2px)`,
          backgroundSize: "40px 40px",
          opacity: MOTION.draw(0.9, 0.35, C.craft, C.curve)(T) * MOTION.draw(1, 0, C.launch + 0.9, C.launch + 1.9)(T),
        }} />
        <Guides T={T} C={C} sc={sc} />
        <Grains T={T} C={C} sc={sc} />
        <div style={{ position: "absolute", left: LX, top: LY, width: 960, height: 1080, transform: `scale(${LS})`, transformOrigin: "0 0" }}>
          <TheP T={T} C={C} sc={sc} />
          <TheC T={T} C={C} sc={sc} />
          <TheL T={T} C={C} />
          <Dots T={T} C={C} />
        </div>
        <Marks T={T} C={C} sc={sc} />
        <Lockup T={T} C={C} total={authoredTotal} />
      </div>
      <ActLabel T={T} C={C} total={authoredTotal} show={showLabels} />
    </div>
  );
}

function PCLLogoFilm() {
  const [t, setTweak] = useTweaks(window.PCL_TWEAKS);
  return (
    <React.Fragment>
      <CompositionStage width={1920} height={1080} scenes={window.OM_SCENES} playback={window.OM_PLAYBACK} bg={PAPER}>
        <Piece showLabels={t.actLabels} />
      </CompositionStage>
      <TweaksPanel>
        <TweakSection label="Sequence" />
        <TweakToggle label="Act labels" value={t.actLabels} onChange={v => setTweak("actLabels", v)} />
        <TweakToggle label="Motion editor" value={t.motionEditor} onChange={v => setTweak("motionEditor", v)} />
      </TweaksPanel>
    </React.Fragment>
  );
}

window.PCLLogoFilm = PCLLogoFilm;
