/**
 * Background shaders for the Hero and CTA blocks.
 *
 * Each entry is the body of a fragment shader; HEAD supplies the uniforms
 * (R size, T time, D 1 when the block's text is light on dark) and the noise
 * helpers. Both pictures keep a calm zone behind the copy: the centre in a
 * tall block (the hero), the left side in a wide one (the CTA), so the text
 * stays readable.
 */
export const HEAD =
	'precision highp float;uniform vec2 R;uniform float T;uniform float D;' +
	'float h(vec2 p){return fract(sin(dot(p,vec2(127.1,311.7)))*43758.5453);}' +
	'float n(vec2 p){vec2 i=floor(p),f=fract(p);f=f*f*(3.-2.*f);return mix(mix(h(i),h(i+vec2(1,0)),f.x),mix(h(i+vec2(0,1)),h(i+vec2(1,1)),f.x),f.y);}' +
	'float fbm(vec2 p){float v=0.,a=.5;for(int i=0;i<5;i++){v+=a*n(p);p=p*2.02+vec2(1.7,9.2);a*=.5;}return v;}' +
	'vec3 pal(float u){vec3 b=vec3(.30,.41,.89),p=vec3(.64,.25,.74),r=vec3(.99,.02,.35);u=clamp(u,0.,1.);return u<.5?mix(b,p,u*2.):mix(p,r,(u-.5)*2.);}' +
	// 1 inside the area that holds the copy: centre for tall blocks, left for wide ones.
	'float calmZone(vec2 uv,float asp){float tall=exp(-pow((uv.x-.5)*asp*.7,2.))*exp(-pow((uv.y-.52)*1.6,2.));float wide=smoothstep(.75,.08,uv.x);return asp>2.6?wide:tall;}';

// Satin: folds of silk lit from one side, shaded from a height field.
export const SATIN =
	'float H(vec2 p,float t){return .5*sin(p.x*1.7+t+1.3*sin(p.y*2.1-t*.7))+.35*sin(p.x*3.1-p.y*2.3+t*.8)+.2*sin(p.y*4.2+p.x*1.1-t*.6);}' +
	'void main(){vec2 uv=gl_FragCoord.xy/R;float asp=R.x/R.y;vec2 p=vec2(uv.x*asp,uv.y)*1.5;float t=T*.16;float e=.01;' +
	'float hh=H(p,t);float hx=(H(p+vec2(e,0.),t)-hh)/e;float hy=(H(p+vec2(0.,e),t)-hh)/e;' +
	'vec3 nr=normalize(vec3(-hx*.42,-hy*.42,1.));vec3 L=normalize(vec3(-.45,.6,.65));' +
	'float dif=clamp(dot(nr,L),0.,1.);vec3 hv=normalize(L+vec3(0.,0.,1.));float sp=pow(clamp(dot(nr,hv),0.,1.),38.);' +
	'float u=clamp(uv.x*.85+hh*.28+.08,0.,1.);vec3 base=pal(u);bool dk=D>.5;' +
	'vec3 bg=dk?vec3(.027,.027,.05):vec3(.965,.96,.985);' +
	'vec3 col=dk?base*(.12+.62*dif)+vec3(1.,.9,.95)*sp*.12:mix(base*(.2+1.0*dif),vec3(1.),.52);' +
	'float m=pow(smoothstep(.0,.9,uv.x*.45+uv.y*.75+.1),1.2);' +
	'm*=1.-.66*calmZone(uv,asp);m*=dk?1.:.66;' +
	'col=mix(bg,col,m);col+=(h(gl_FragCoord.xy+T)-.5)/180.;gl_FragColor=vec4(col,1.);}';

// Dusk: a sky in the brand colours, clouds lit from below, a few stars in the dark.
export const DUSK =
	'void main(){vec2 uv=gl_FragCoord.xy/R;float asp=R.x/R.y;float t=T*.02;float y=uv.y;bool dk=D>.5;' +
	'vec3 top=dk?vec3(.03,.03,.08):vec3(.91,.91,.99);vec3 m1=dk?vec3(.09,.10,.32):vec3(.80,.82,.98);vec3 m2=dk?vec3(.30,.11,.42):vec3(.93,.82,.96);vec3 hor=dk?vec3(.66,.12,.31):vec3(1.,.82,.86);' +
	'vec3 c=mix(hor,m2,smoothstep(0.,.3,y));c=mix(c,m1,smoothstep(.2,.58,y));c=mix(c,top,smoothstep(.5,1.,y));' +
	'float g=exp(-pow((uv.x-.5)*asp*1.1,2.))*exp(-pow(y/.3,2.));c+=(dk?vec3(.5,.16,.3):vec3(1.,.84,.8))*g*.32;' +
	'vec2 q=vec2(uv.x*asp*1.1+t*4.,y*5.5);float cl=fbm(q+fbm(vec2(uv.x*asp*.9-t*3.,y*3.5))*1.4);' +
	'float band=smoothstep(.0,.2,y)*(1.-smoothstep(.34,.72,y));float m=smoothstep(.46,.78,cl)*band;' +
	'vec3 cc=mix(dk?vec3(.14,.09,.32):vec3(.96,.93,.99),hor,(1.-smoothstep(0.,.38,y))*(dk?.6:.5));' +
	'float rim=smoothstep(.46,.52,cl)*(1.-smoothstep(.52,.62,cl))*band;cc+=hor*rim*.22;' +
	'c=mix(c,cc,m*.7);' +
	'c=mix(c,mix(top,m1,.35),calmZone(uv,asp)*.72);' +
	'if(dk){vec2 sg=floor(gl_FragCoord.xy/2.);float st=step(.9975,h(sg));st*=smoothstep(.5,.9,y)*(.5+.5*sin(T*1.6+h(sg+3.)*40.));c+=vec3(.8,.85,1.)*st*.7;}' +
	'c+=(h(gl_FragCoord.xy+T)-.5)/170.;gl_FragColor=vec4(c,1.);}';

// Aurora: curtains of light high in a dark sky, pale veils in light mode.
export const AURORA =
	'void main(){vec2 uv=gl_FragCoord.xy/R;float asp=R.x/R.y;float t=T*.05;bool dk=D>.5;float x=uv.x*asp;' +
	'float w=fbm(vec2(x*.9+t*1.5,t*.7));float yy=uv.y+(w-.5)*.5;' +
	'float band=smoothstep(.12,.45,yy)*(1.-smoothstep(.7,1.,yy));' +
	'float rays=pow(fbm(vec2(x*7.+w*3.,t*2.)),2.2);float inten=band*(.3+1.15*rays);' +
	'vec3 col=mix(vec3(.05,.78,.95),pal(.5),smoothstep(.25,.85,yy));col=mix(col,pal(.95),smoothstep(.72,1.,yy)*.55);' +
	'vec3 bg=dk?mix(vec3(.02,.02,.06),vec3(.05,.06,.15),uv.y):vec3(.955,.955,.99);' +
	'vec3 c=dk?bg+col*inten*.9:mix(bg,mix(col,vec3(1.),.35),inten*.75);' +
	'c=mix(c,bg,calmZone(uv,asp)*.72);' +
	'if(dk){vec2 sg=floor(gl_FragCoord.xy/2.);float st=step(.9972,h(sg));st*=smoothstep(.45,.85,uv.y)*(.5+.5*sin(T*1.5+h(sg+3.)*40.));c+=vec3(.8,.9,1.)*st*.7;}' +
	'c+=(h(gl_FragCoord.xy+T)-.5)/170.;gl_FragColor=vec4(c,1.);}';

// Nebula: drifting clouds of blue and magenta dust with a cyan core and faint stars.
export const NEBULA =
	'void main(){vec2 uv=gl_FragCoord.xy/R;float asp=R.x/R.y;bool dk=D>.5;vec2 p=vec2(uv.x*asp,uv.y)*1.6;float t=T*.03;' +
	'vec2 q=vec2(fbm(p+t),fbm(p+5.2-t));float a=fbm(p+3.*q);float b=fbm(p*1.3+4.*q+9.);' +
	'vec3 col=pal(.12)*smoothstep(.35,.8,a)+pal(.72)*smoothstep(.4,.85,b)*.9+vec3(.1,.7,.95)*smoothstep(.62,.9,a*b*1.7)*.4;' +
	'vec3 bg=dk?vec3(.02,.02,.05):vec3(.955,.955,.985);float k=1.-calmZone(uv,asp)*.7;' +
	'vec3 c=dk?bg+col*.9*k:mix(bg,mix(pal(.15+b*.7),vec3(1.),.35),clamp(a*b*2.4,0.,1.)*.7*k);' +
	'if(dk){vec2 sg=floor(gl_FragCoord.xy/2.);float st=step(.9968,h(sg));st*=(.5+.5*sin(T*1.2+h(sg+3.)*40.));c+=vec3(.85,.9,1.)*st*.65*k;}' +
	'c+=(h(gl_FragCoord.xy+T)-.5)/170.;gl_FragColor=vec4(c,1.);}';

export const SHADERS = {
	satin: SATIN,
	dusk: DUSK,
	aurora: AURORA,
	nebula: NEBULA,
};
