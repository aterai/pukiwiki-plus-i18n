import javafx.animation.*;
import javafx.scene.*;
import javafx.scene.image.*;
import javafx.scene.paint.*;
import javafx.scene.shape.*;
import javafx.scene.transform.*;
import javafx.stage.*;

public class SlitCircleRotation extends CustomNode {
    public var ox: Number;
    public var oy: Number;
    public var slitCount:   Number = 3;
    public var strokeWidth: Number = 16;
    public var outerRadius: Number = 40;
    public var innerRadius: Number = outerRadius - strokeWidth;
    var angle : Number = 0;
    var timeline = Timeline {
      repeatCount: Timeline.INDEFINITE
      keyFrames : [
          KeyFrame {
            time: 0s;
            values : [ angle => 0.0 ]
          },
          KeyFrame {
            time: 4s;
            values : [ angle => 360.0 tween Interpolator.LINEAR ]
          } ]
    };
    override public function create(): Node {
        timeline.play();
        return ShapeSubtract {
          fill: Color.ORANGE,
          opacity: 0.4,
          transforms: bind [Transform.translate(ox, oy), Transform.rotate(angle, 0, 0) ],
          a: Circle { radius: outerRadius },
          b: [ for ( i in [ 0..slitCount-1 ] )
               Rectangle {
                   x: 0, y: -strokeWidth / 2,
                   width: outerRadius, height: strokeWidth,
                   transforms: Transform.rotate(i*360.0/slitCount, 0, 0),
               },
               Circle { radius: innerRadius }, ]
        };
    }
}
function run() {
    Stage {
      scene: Scene {content: [
          ImageView { image: Image {url:"http://terai.xrea.jp/data/jfx/image.jpg"} },
          Rectangle { width:320, height:240, fill:Color.GREY, opacity:0.5 },
          SlitCircleRotation{ ox: 100, oy: 80 } ]}
      title  : "JavaFX - Icon Animation"
      width  : 320
      height : 240
      visible: true
    }
}
