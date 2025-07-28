import{d as f,e as v,c as m}from"./index-DfbCg4Oz.js";import{d as w,y as c,b as C,o as y,u as i,n as g,w as k,j as b,X as l}from"./app-CRVfHUbl.js";const x=f("button",{variants:{variant:{default:"",destructive:"",outline:"",secondary:"",ghost:"",link:""},size:{default:"h-9 px-4 py-2",sm:"h-8 rounded-md px-3 text-xs",lg:"h-10 rounded-md px-8",icon:"h-9 w-9"}},defaultVariants:{variant:"default",size:"default"}}),D=w({__name:"Button",props:{variant:{default:"default"},size:{},class:{},dataType:{default:"primary"},asChild:{type:Boolean},as:{default:"button"}},setup(e){const a=e,s=c(()=>a.variant),t=c(()=>a.variant==="default"?a.dataType:{destructive:"destructive",outline:"outline",secondary:"secondary",ghost:"ghost",link:"link"}[a.variant||"default"]||"primary");return(o,r)=>(y(),C(i(v),{as:o.as,"as-child":o.asChild,class:g(i(m)(i(x)({variant:s.value,size:o.size}),a.class)),"data-type":t.value},{default:k(()=>[b(o.$slots,"default")]),_:3},8,["as","as-child","class","data-type"]))}});/**
 * @license lucide-vue-next v0.488.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const u=e=>e.replace(/([a-z0-9])([A-Z])/g,"$1-$2").toLowerCase(),T=e=>e.replace(/^([A-Z])|[\s-_]+(\w)/g,(a,s,t)=>t?t.toUpperCase():s.toLowerCase()),B=e=>{const a=T(e);return a.charAt(0).toUpperCase()+a.slice(1)},z=(...e)=>e.filter((a,s,t)=>!!a&&a.trim()!==""&&t.indexOf(a)===s).join(" ").trim();/**
 * @license lucide-vue-next v0.488.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */var n={xmlns:"http://www.w3.org/2000/svg",width:24,height:24,viewBox:"0 0 24 24",fill:"none",stroke:"currentColor","stroke-width":2,"stroke-linecap":"round","stroke-linejoin":"round"};/**
 * @license lucide-vue-next v0.488.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const _=({size:e,strokeWidth:a=2,absoluteStrokeWidth:s,color:t,iconNode:o,name:r,class:$,...p},{slots:d})=>l("svg",{...n,width:e||n.width,height:e||n.height,stroke:t||n.stroke,"stroke-width":s?Number(a)*24/Number(e):a,class:z("lucide",...r?[`lucide-${u(B(r))}-icon`,`lucide-${u(r)}`]:["lucide-icon"]),...p},[...o.map(h=>l(...h)),...d.default?[d.default()]:[]]);/**
 * @license lucide-vue-next v0.488.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const L=(e,a)=>(s,{slots:t})=>l(_,{...s,iconNode:a,name:e},t);export{D as _,L as c};
