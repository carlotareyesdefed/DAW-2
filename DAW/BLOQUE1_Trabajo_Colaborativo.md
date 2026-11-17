# 🃏 BLOQUE 1. Trabajo colaborativo con Git y GitHub

## Proyecto: documentación del juego de cartas UNO

**Objetivo.** Crear y mejorar, entre dos o tres personas, la documentación de un juego de cartas utilizando un mismo repositorio. Practicaremos ramas, commits, sincronización, Pull Requests (PR), revisión de código, resolución de conflictos y etiquetas.

**Forma de trabajo.** Cada pareja (o trío) dispone de un repositorio propio en GitHub. Todas las personas del equipo deben participar desde su propia cuenta. **No se trabaja directamente sobre `main`: cada tarea se desarrolla en una rama y se incorpora mediante un PR.**

**Resultado esperado.** El repositorio debe contener una documentación comprensible del juego, un historial de aportaciones individuales, PR revisados y un conflicto de Git resuelto de forma colaborativa.

---

## 1. Acceso al repositorio y reparto de tareas

1. Localizad en GitHub el repositorio asignado a vuestro equipo, dentro de la organización `ESCUELA-ARTE`. Si tenéis una invitación pendiente, aceptadla antes de continuar.
2. Cada integrante debe clonarlo **en su propio ordenador**. Sustituid `NOMBRE_REPOSITORIO` por el nombre real:

   ```bash
   git clone https://github.com/ESCUELA-ARTE/NOMBRE_REPOSITORIO.git
   cd NOMBRE_REPOSITORIO
   ```

3. Comprobad que existe `README.md` y que la rama inicial es `main`:

   ```bash
   git branch --show-current
   git status
   ```

4. Repartid estas responsabilidades. En un equipo de dos se realizan A y B; en un equipo de tres se añade C.

| Persona | Rama de trabajo | Archivo | Contenido |
|---|---|---|---|
| A | `feature-reglas` | `reglas.md` | Preparación, turno, cartas especiales y final de partida |
| B | `feature-estrategias` | `estrategias.md` | Consejos de juego y dos variantes de reglas explicadas claramente |
| C (solo tríos) | `feature-historia` | `historia.md` | Historia del juego y tres curiosidades documentadas |

**Importante:** los nombres de las ramas deben respetarse. No escribáis dos personas a la vez en un mismo ordenador ni compartáis una misma cuenta de GitHub.

---

## 2. Primera contribución individual

Cada integrante realiza el trabajo siguiente desde su propio ordenador y en su rama. Los ejemplos utilizan la tarea de la persona A: B y C deberán cambiar el nombre de la rama y del archivo por los que les correspondan.

### Paso 1. Crear la rama

```bash
git switch main
git pull --ff-only origin main
git switch -c feature-reglas
```

`git switch -c` crea una rama nueva y cambia a ella. Comprobadlo con `git branch --show-current`.

### Paso 2. Crear el documento

Cread `reglas.md` con un título, una breve introducción y, al menos, dos apartados. Por ejemplo:

```markdown
# Reglas del UNO

## Objetivo

[Explicad qué debe conseguir cada jugador.]

## Preparación

[Explicad cómo comienza una partida.]
```

Cada persona debe aportar **información desarrollada**, no solo títulos o frases pendientes de completar. Usad vuestras propias palabras y, cuando consultéis una fuente externa, indicad su enlace en el documento.

### Paso 3. Hacer dos commits

Realizad una primera parte del archivo y guardadla:

```bash
git add reglas.md
git commit -m "Crear estructura de las reglas"
```

Después, completad y mejorad el contenido. Guardad de nuevo:

```bash
git add reglas.md
git commit -m "Ampliar reglas y cartas especiales"
```

El segundo commit debe contener cambios reales. Comprobad el historial y el estado:

```bash
git log --oneline -3
git status
```

### Paso 4. Publicar la rama y abrir el PR

```bash
git push -u origin feature-reglas
```

En GitHub, abrid un **Pull Request de vuestra rama hacia `main`**. Escribid un título que indique qué habéis añadido y una descripción breve de los contenidos. **No fusionéis todavía el PR**: antes tiene que revisarlo otra persona del equipo.

---

## 3. Revisar el trabajo del compañero

Cada persona revisa el PR de otra persona: en parejas, A revisa a B y B revisa a A; en tríos, A revisa a B, B revisa a C y C revisa a A.

1. Entrad en **Pull requests** y abrid el PR que os corresponde.
2. Acceded a **Files changed** y comprobad si el documento tiene contenido suficiente, está ordenado y se entiende.
3. Añadid **al menos un comentario útil** sobre un fragmento concreto. No basta con escribir «bien» o «correcto»: señalad algo que mejorar o destacad una decisión explicando el motivo.
4. Si hace falta cambiar algo, pedid una modificación. La persona autora editará su archivo **en la misma rama**, hará otro commit y ejecutará `git push`.
5. Revisad de nuevo y aprobád el PR cuando esté listo. Entonces se puede usar **Merge pull request** para incorporarlo a `main`.

> La aprobación y la fusión son acciones distintas: aprobar un PR no incorpora por sí solo los cambios a `main`.

Cuando todos los PR estén fusionados, cada participante actualizará su copia local:

```bash
git switch main
git pull --ff-only origin main
```

Comprobad que en `main` aparecen `reglas.md` y `estrategias.md` y, en los tríos, también `historia.md`.

---

## 4. Provocar un conflicto de forma controlada

En esta fase, **todas las personas deben partir del mismo estado de `main` y crear su rama antes de que se fusione ningún PR de esta fase**.

### Paso 1. Preparar ramas independientes

Cada integrante, en su ordenador, ejecuta:

```bash
git switch main
git pull --ff-only origin main
```

Ahora crea una rama con un nombre propio, usando su usuario de GitHub. Por ejemplo:

```bash
git switch -c feature-lema-miusuario
```

### Paso 2. Modificar exactamente la misma línea

Abrid `README.md` y localizad este apartado, que ya está incluido en el repositorio:

```markdown
## Lema del equipo
Pendiente de decidir.
```

**Cada integrante sustituye únicamente la línea `Pendiente de decidir.` por un lema diferente**, sin borrar el título. Por ejemplo:

- Persona A: `Las mejores partidas empiezan con una buena estrategia.`
- Persona B: `En el UNO, cada carta puede cambiarlo todo.`
- Persona C (si la hay): `Colaborar también es saber cuándo cambiar de plan.`

Cada persona guarda el cambio y ejecuta (sustituyendo `miusuario` por el que haya utilizado en su rama):

```bash
git add README.md
git commit -m "Proponer lema del equipo"
git push -u origin feature-lema-miusuario
```

### Paso 3. Abrir todos los PR antes de fusionar

1. Cada persona abre un PR de su rama `feature-lema-*` hacia `main`.
2. **Esperad hasta que todos los PR estén abiertos.** No ejecutéis `git pull` sobre estas ramas para incorporar los cambios de los demás antes de terminar este paso.
3. Fusionad **solo uno** de los PR.
4. Revisad los PR restantes: al intentar integrarlos con `main` habrá cambios incompatibles sobre la misma línea de `README.md`.

Si el primer PR ya se había fusionado antes de que alguien creara su rama, esa persona deberá repetir el ejercicio desde una versión común de `main`, porque puede que no aparezca el conflicto.

---

## 5. Resolver el conflicto

La persona cuyo PR ha quedado bloqueado realizará la resolución. Se puede usar el editor de conflictos de GitHub, si está disponible para ese PR, o trabajar localmente. Aquí se muestra la resolución local, que también funciona si GitHub no ofrece el botón de resolución.

### Paso 1. Incorporar `main` a la rama pendiente

```bash
git switch feature-lema-miusuario
git fetch origin
git merge origin/main
```

Git detendrá el `merge` al detectar el conflicto. Abrid `README.md`: veréis unas marcas parecidas a estas (las frases concretas pueden variar):

```text
<<<<<<< HEAD
En el UNO, cada carta puede cambiarlo todo.
=======
Las mejores partidas empiezan con una buena estrategia.
>>>>>>> origin/main
```

### Paso 2. Redactar un resultado conjunto

Eliminad **las marcas de conflicto** y redactad una única versión que recoja las ideas de ambas propuestas. Por ejemplo:

```markdown
## Lema del equipo
La estrategia cambia cada partida, y colaborar nos ayuda a mejorar.
```

Guardad el archivo y completad la fusión:

```bash
git add README.md
git commit -m "Resolver conflicto y acordar lema del equipo"
git push
```

Volved al PR y comprobad que ya es posible fusionarlo. En un trío, repetid este procedimiento con el tercer PR si también queda bloqueado. Antes de fusionarlo, el lema final debe reflejar el acuerdo de las tres personas.

---

## 6. Comprobación y cierre

Cuando todos los PR estén integrados, una persona del equipo actualizará `main` y creará una etiqueta para señalar el resultado:

```bash
git switch main
git pull --ff-only origin main
git tag -a v1.0 -m "Documentación colaborativa de UNO"
git push origin v1.0
```

**La etiqueta la crea una sola persona por repositorio**, para que no se duplique.

Después, cada integrante puede eliminar **sus propias ramas locales ya fusionadas**. Por ejemplo:

```bash
git branch -d feature-reglas
```

No ejecutéis este último comando en la rama que estáis intentando borrar. Cambiad antes a `main`.

### Lista de comprobación de la entrega

- [ ] Cada integrante ha trabajado con su cuenta y su propia rama.
- [ ] Cada integrante ha hecho al menos dos commits en la fase de documentación.
- [ ] El repositorio contiene los dos documentos obligatorios (y el tercero en los tríos).
- [ ] Los PR de documentación incluyen revisión de un compañero.
- [ ] Se han abierto PR distintos para las propuestas del lema.
- [ ] Se ha producido y resuelto un conflicto sobre `README.md`.
- [ ] En `main` aparece un lema conjunto, sin marcas de conflicto.
- [ ] Se ha creado y publicado la etiqueta `v1.0`.

**Entrega:** comunicad el enlace del repositorio del equipo y comprobad que los commits y PR permiten identificar lo que hizo cada persona.
