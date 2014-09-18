import javafx.animation.*;
import javafx.scene.*;
import javafx.scene.image.*;
import javafx.scene.input.*;
import javafx.scene.paint.*;
import javafx.scene.shape.*;
import javafx.scene.transform.*;
import javafx.stage.*;

class HolePunchTransition extends CustomNode {
    def r  = -45;
    def d  = 16;
    def w  = 320;
    def h  = 240;
    var xp = 0;
    var op = 0.0;
    var index = 0;
    var images: Image[];
    var image: Image = images[index];
    var current: Image;
    var next: Image;

    def timeline:Timeline = Timeline {
      keyFrames:[
          at(0s)   { op => 0.0; image => current },
          at(0.2s) { op => 0.0 tween Interpolator.LINEAR },
          at(1s)   { op => 1.0; xp => 0; image => next },
          at(1.4s) { xp => 0 tween Interpolator.LINEAR },
          at(3s)   { xp => w+h }
          ]
    };
    override public function create(): Node {
        def circles:Circle[] = for (j in [0..w/d/2], i in [0..((w+h)/d)]) Circle {
          cache: true,
          blocksMouse: true,
          focusable: false,
          radius: j,
          centerX: w-j*d,
          centerY: i*d,
        };
        var mask = ShapeSubtract {
          fill: Color.WHITE,
          opacity: bind op;
          transforms: bind [Transform.rotate(r, 0, h), Transform.translate(-w+xp, 0)],
          a: Rectangle { x:0, y:0, width:w+400, height:h*2 }, //400=sqrt(w*w+h*h)
          b: [ circles, Rectangle {
            x:-w, y:0, width:w+w/2, height:w+h,
          }]
        };
//         var mask = ImageView {
//           image: Image{ url: "http://terai.xrea.jp/data/jfx/mask.gif" },
//           opacity: bind op;
//           transforms: bind [Transform.rotate(r, 0, h), Transform.translate(-400+xp, 0)],
//         };
        return Group {
          content: [ ImageView {
            onMouseClicked: function(e:MouseEvent):Void {
                if(not timeline.running) {
                    current = images[index];
                    index++;
                    if(index>=(sizeof images)) index = 0;
                    next = images[index];
                    timeline.playFromStart();
                }
            },
            image: bind image,
          }, mask,
          //Rectangle{width:w, height:h, fill:null, stroke: Color.GREEN }
          ]
        };
    }
}
function run() {
    Stage {
      scene: Scene {
        //width: 640, height: 480,
        width: 320, height: 240,
        content: HolePunchTransition{
          //transforms: Transform.translate(120, 120),
          images: [Image{ url: "http://terai.xrea.jp/data/jfx/001.jpg" },
                   Image{ url: "http://terai.xrea.jp/data/jfx/002.jpg" },
                   Image{ url: "http://terai.xrea.jp/data/jfx/003.jpg" }]
//           images: [Image{ url: "{__DIR__}001.jpg" },
//                    Image{ url: "{__DIR__}002.jpg" },
//                    Image{ url: "{__DIR__}003.jpg" }]
        }
      }
      title: "JavaFX - Hole Punch Transition"
      resizable: false, visible: true
    }
}
