use rltk::{Rltk, GameState};

struct State {x: i32, y :i32}
impl GameState for State {
    fn tick(&mut self, ctx : &mut Rltk) {
        ctx.cls();
        ctx.print(self.x, self.y, "Hello Rust World");

        if self.x > 10 {
            self.x = 0;
        }
        self.x = self.x + 1;

        if self.y > 10 {
            self.y = 0;
        }
        self.y = self.y + 1;
    }
}

fn main() -> rltk::BError {
    use rltk::RltkBuilder;
    let context = RltkBuilder::simple80x50()
        .with_title("Roguelike Tutorial")
        .build()?;
    let gs = State{ x:1, y:1};
    rltk::main_loop(context, gs)
}